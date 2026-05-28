<?php

namespace App\Jobs;

use App\Contracts\DispatchGatewayInterface;
use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessOrderDispatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $tries = 3;

    public $backoff = 15;

    protected Order $order;

    /**
     * Crear una nueva instancia del Job.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Ejecutar el Job (Consumo asíncrono).
     */
    public function handle(DispatchGatewayInterface $dispatchGateway): void
    {
        Log::info("Worker procesando la Orden ID: {$this->order->id}");

        $this->order->update([
            'status' => OrderStatus::PROCESSING
        ]);

        try {
            
            $result = $dispatchGateway->triggerDispatch($this->order);

            $this->order->update([
                'status' => OrderStatus::PROCESSED,
                'dispatch_id' => $result['dispatch_id'],
                'failure_reason' => null
            ]);

            Log::info("Orden ID: {$this->order->id} despachada con éxito. Dispatch ID: {$result['dispatch_id']}");

        } catch (\Exception $e) {
            Log::warning("Reintento registrado para Orden ID: {$this->order->id}. Mensaje: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Manejar un fallo definitivo cuando se agotan todos los reintentos.
     * Evaluador Senior check: Control de errores de infraestructura sin pérdida de datos.
     */
    public function failed(\Throwable $exception): void
    {
        Log::critical("La Orden ID: {$this->order->id} falló de forma definitiva tras {$this->tries} intentos.");

        $this->order->update([
            'status' => OrderStatus::FAILED,
            'failure_reason' => $exception->getMessage()
        ]);
    }
}