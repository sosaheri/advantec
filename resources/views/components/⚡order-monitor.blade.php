<div wire:poll.2s>
    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="margin: 0; color: #1a202c;">📊 Panel de Control y Monitoreo</h2>
            <p style="margin: 5px 0 0 0; color: #718096; font-size: 14px;">Las órdenes se actualizan automáticamente cada 2 segundos.</p>
        </div>
        <div style="display: flex; align-items: center; font-size: 13px; color: #4a5568;">
            <span style="height: 10px; width: 10px; background-color: #48bb78; border-radius: 50%; display: inline-block; margin-right: 8px; animate: pulse 2s infinite;"></span>
            Escuchando cambios...
        </div>
    </div>

    <div style="background: white; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
            <thead style="background-color: #f7fafc; border-bottom: 2px solid #e2e8f0; color: #4a5568; font-weight: bold;">
                <tr>
                    <th style="padding: 12px 16px;">ID</th>
                    <th style="padding: 12px 16px;">Cliente</th>
                    <th style="padding: 12px 16px;">Monto</th>
                    <th style="padding: 12px 16px;">Estado</th>
                    <th style="padding: 12px 16px;">ID Despacho (Node)</th>
                    <th style="padding: 12px 16px;">Detalles / Alertas</th>
                </tr>
            </thead>
            <tbody style="color: #2d3748;">
                @forelse($orders as $order)
                    <tr style="border-bottom: 1px solid #edf2f7; background-color: {{ $loop->index % 2 === 0 ? '#ffffff' : '#fcfcfc' }}; transition: background-color 0.3s;">
                        <td style="padding: 12px 16px; font-weight: bold;">#{{ $order->id }}</td>
                        <td style="padding: 12px 16px;">
                            <div style="font-weight: 500;">{{ $order->customer_name }}</div>
                            <div style="font-size: 12px; color: #718096;">{{ $order->customer_email }}</div>
                        </td>
                        <td style="padding: 12px 16px; font-weight: 600;">${{ number_format($order->total_amount, 2) }}</td>
                        <td style="padding: 12px 16px;">
                            @if($order->status->value === 'pending')
                                <span style="background-color: #feebc8; color: #c05621; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold;">Pendiente</span>
                            @elseif($order->status->value === 'processing')
                                <span style="background-color: #e2e8f0; color: #4a5568; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; display: inline-flex; align-items: center;">
                                    Procesando...
                                </span>
                            @elseif($order->status->value === 'processed')
                                <span style="background-color: #c6f6d5; color: #22543d; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold;">Despachado</span>
                            @elseif($order->status->value === 'failed')
                                <span style="background-color: #fed7d7; color: #9b2c2c; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold;">Fallido</span>
                            @endif
                        </td>
                        <td style="padding: 12px 16px; font-family: monospace; font-weight: bold; color: #4a5568;">
                            {{ $order->dispatch_id ?? '—' }}
                        </td>
                        <td style="padding: 12px 16px; font-size: 12px;">
                            @if($order->status->value === 'failed')
                                <span style="color: #e53e3e; font-weight: 500;" title="{{ $order->failure_reason }}">
                                    ⚠️ {{ Str::limit($order->failure_reason, 40) }}
                                </span>
                            @elseif($order->status->value === 'processed')
                                <span style="color: #38a169; font-weight: 500;">✅ Sincronizado con Node.js</span>
                            @else
                                <span style="color: #a0aec0;">Esperando acción...</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 32px; text-align: center; color: #a0aec0;">
                            No hay órdenes registradas en el sistema. ¡Lanza una petición HTTP para empezar!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>