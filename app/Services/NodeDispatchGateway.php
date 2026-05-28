<?php

namespace App\Services;

use App\Contracts\DispatchGatewayInterface;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NodeDispatchGateway implements DispatchGatewayInterface
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.dispatch.url', 'http://mock-dispatch-service:3000');
    }

    public function triggerDispatch(Order $order): array
    {
        Log::info("Iniciando solicitud de despacho para la Orden ID: {$order->id}");

        try {

            $response = Http::timeout(5)
                ->retry(3, 100) 
                ->post("{$this->baseUrl}/api/v1/dispatch", [
                    'order_id' => $order->id,
                    'amount'   => (float) $order->total_amount,
                ]);

            if ($response->failed()) {
                Log::error("El servicio de despacho falló para la Orden {$order->id}. Status: " . $response->status());
                throw new \Exception("Error externo de logística: HTTP Status " . $response->status());
            }

            $data = $response->json();

            if (!isset($data['status']) || $data['status'] !== 'success' || !isset($data['dispatch_id'])) {
                throw new \Exception("Estructura de respuesta inválida del servicio de logística externa.");
            }

            return [
                'status' => 'success',
                'dispatch_id' => $data['dispatch_id'],
                'external_response' => $data
            ];

        } catch (\Exception $e) {
            Log::critical("Fallo crítico en la comunicación con Node.js para la Orden {$order->id}: " . $e->getMessage());
            throw $e;
        }
    }
}