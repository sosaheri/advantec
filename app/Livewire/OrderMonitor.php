<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

class OrderMonitor extends Component
{
    use WithPagination;

    public ?string $feedbackMessage = null;

    public bool $isErrorMessage = false;

    /**
     * Genera una orden rápida y la despacha al job a través del comando existente.
     */
    public function dispatchQuickOrder(): void
    {
        try {
            $randomCount = random_int(1, 50);
            Artisan::call('ecosystem:simulate', ['count' => $randomCount]);

            $this->feedbackMessage = "Se enviaron {$randomCount} órdenes al procesamiento asíncrono.";
            $this->isErrorMessage = false;
        } catch (Throwable $exception) {
            report($exception);

            $this->feedbackMessage = 'No se pudo generar la orden. Revisa logs y el estado de servicios.';
            $this->isErrorMessage = true;
        }
    }

    /**
     * Renderiza el componente y recupera las órdenes actualizadas.
     */
    public function render()
    {
        $orders = Order::latest()->paginate(10);

        return view('livewire/order-monitor', [
            'orders' => $orders,
        ]);
    }
}