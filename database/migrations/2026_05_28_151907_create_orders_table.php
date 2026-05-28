<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->decimal('total_amount', 10, 2);
            
            // Estado de la orden usando los valores del Enum (Por defecto: pending)
            $table->string('status')->default(\App\Enums\OrderStatus::PENDING->value);
            
            // Código de despacho externo (Nullabe porque se genera asíncronamente en la Fase 4)
            $table->string('dispatch_id')->nullable();
            
            // Para auditoría técnica en caso de fallos en la cola
            $table->text('failure_reason')->nullable();
            
            $table->timestamps();

            // ⚡ ÍNDICES DE RENDIMIENTO (Crucial para pruebas Senior)
            $table->index('status'); // Optimiza el panel de Livewire al filtrar por estado
            $table->unique('dispatch_id'); // Garantiza consistencia con el servicio externo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
