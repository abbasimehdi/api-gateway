<?php

namespace App\Providers;

use App\Services\Gateway\GatewayProxy;
use App\Services\Gateway\ServiceRegistry;
use Illuminate\Support\ServiceProvider;

class GatewayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ServiceRegistry::class);
        $this->app->singleton(GatewayProxy::class);
    }

    public function boot(): void
    {
        //
    }
}