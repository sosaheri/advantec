<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Jobs\ProcessOrderDispatch;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SimulateOrderTraffic extends Command
{
    /**
     * El nombre y la firma del comando para la terminal.
     * Permite definir opcionalmente cuántas órdenes se inyectarán (por defecto 20).
     *
     * @var string
     */
    protected $signature = 'ecosystem:simulate {count=20 : Número de órdenes a generar}';

    /**
     * La descripción que aparecerá cuando ejecutes php artisan list.
     *
     * @var string
     */
    protected $description = 'Simula tráfico masivo inyectando órdenes asíncronas en el ecosistema';

    /**
     * Ejecutar el comando de consola.
     */
    public function handle()
    {
        $count = (int) $this->argument('count');
        $this->info("🚀 Iniciando simulador de estrés: Generando {$count} órdenes...");

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $firstNames = ['Heriberto', 'Carlos', 'Ana', 'Luis', 'Maria', 'Jose', 'Pedro', 'Laura'];
        $lastNames = ['Sosa', 'Hernandez', 'Vivas', 'Oropeza', 'Rodriguez', 'Gomez', 'Perez'];

        for ($i = 0; $i < $count; $i++) {
            $name = $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
            $email = strtolower(Str::slug($name)) . $i . '@example.com';
            
            $order = Order::create([
                'customer_name'  => $name,
                'customer_email' => $email,
                'total_amount'   => mt_rand(1000, 50000) / 100, // Genera montos aleatorios entre $10.00 y $500.00
            ]);

            ProcessOrderDispatch::dispatch($order);

            $bar->advance();
            
            usleep(50000); 
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("✅ ¡Éxito! {$count} órdenes inyectadas en la cola de procesamiento.");
        $this->comment("👉 Revisa tu panel web en http://127.0.0.1:8000 para ver las transiciones en tiempo real.");
    }
}