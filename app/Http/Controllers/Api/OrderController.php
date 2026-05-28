<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Jobs\ProcessOrderDispatch;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Crear una nueva orden y despachar su procesamiento asíncrono.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        Log::info("API: Recibiendo solicitud de creación de orden para {$request->input('customer_email')}");

        try {
           
            $order = Order::create($request->validated());

            
            ProcessOrderDispatch::dispatch($order);

            
            return response()->json([
                'status'  => 'success',
                'message' => 'Orden recibida correctamente y enviada a procesamiento.',
                'data'    => [
                    'order_id'       => $order->id,
                    'current_status' => $order->status->value, // Accedemos al valor del Enum
                    'total_amount'   => (float) $order->total_amount,
                    'created_at'     => $order->created_at->toIso8601String()
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error("API Error al crear orden: " . $e->getMessage());
            
            return response()->json([
                'status'  => 'error',
                'message' => 'Hubo un fallo interno al procesar la orden en el servidor.'
            ], 500);
        }
    }
}