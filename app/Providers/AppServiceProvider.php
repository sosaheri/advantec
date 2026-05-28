<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\DispatchGatewayInterface;
use App\Services\NodeDispatchGateway;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DispatchGatewayInterface::class, NodeDispatchGateway::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
