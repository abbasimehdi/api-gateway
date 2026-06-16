<?php
// app/Services/Gateway/Registries/ConsulServiceRegistry.php

namespace App\Services\Gateway\Registries;

use App\Services\Gateway\Contracts\ServiceRegistryInterface;
use App\Services\Gateway\Exceptions\ServiceNotFoundException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ConsulServiceRegistry implements ServiceRegistryInterface
{
    protected const CACHE_TTL = 60; // seconds

    public function __construct(
        protected string $consulUrl,    // e.g., http://consul:8500
        protected string $consulDatacenter = 'dc1'
    ) {}

    public function getBaseUrl(string $service): string
    {
        $cacheKey = "consul.service.{$service}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($service) {
            return $this->fetchServiceUrl($service);
        });
    }

    protected function fetchServiceUrl(string $service): string
    {
        // Query Consul for healthy service instances
        $response = Http::get("{$this->consulUrl}/v1/health/service/{$service}", [
            'dc' => $this->consulDatacenter,
            'passing' => true,
        ]);

        if ($response->failed()) {
            throw new ServiceNotFoundException(
                "Failed to query Consul for service '{$service}'."
            );
        }

        $instances = $response->json();

        if (empty($instances)) {
            throw new ServiceNotFoundException(
                "No healthy instances for service '{$service}'."
            );
        }

        // Pick the first healthy instance (round‑robin could be added)
        $instance = $instances[0];
        $address = $instance['Service']['Address'] ?? $instance['Node']['Address'];
        $port = $instance['Service']['Port'];

        // Optionally, if the service registers with a path (like /api), you can store it separately.
        return "http://{$address}:{$port}";
    }

    /**
     * Manually clear the cache for a service (e.g., after deployment).
     */
    public function forget(string $service): void
    {
        Cache::forget("consul.service.{$service}");
    }
}