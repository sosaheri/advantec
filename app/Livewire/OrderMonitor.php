<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class OrderMonitor extends Component
{
    /**
     * Renderiza el componente y recupera las órdenes actualizadas.
     */
    public function render()
    {
        $orders = Order::latest()->take(10)->get();

        return view('livewire/order-monitor', [
            'orders' => $orders
        ]);
    }
}