<?php
// app/Providers/GatewayServiceProvider.php

namespace App\Providers;

use App\Services\Gateway\Contracts\ServiceRegistryInterface;
use App\Services\Gateway\GatewayProxy;
use App\Services\Gateway\Registries\ConfigServiceRegistry;
use App\Services\Gateway\Registries\ConsulServiceRegistry;
use Illuminate\Support\ServiceProvider;

class GatewayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind the registry implementation based on environment
        $this->app->singleton(ServiceRegistryInterface::class, function ($app) {
            $registryType = env('GATEWAY_REGISTRY', 'config');

            return match ($registryType) {
                'consul' => new ConsulServiceRegistry(
                    consulUrl: env('CONSUL_URL', 'http://consul:8500'),
                    consulDatacenter: env('CONSUL_DATACENTER', 'dc1')
                ),
                default => new ConfigServiceRegistry($app->make('config')),
            };
        });

        $this->app->singleton(GatewayProxy::class);
    }

    public function boot(): void
    {
        //
    }
}