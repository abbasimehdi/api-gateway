<?php
// app/Services/Gateway/Registries/ConfigServiceRegistry.php

namespace App\Services\Gateway\Registries;

use App\Services\Gateway\Contracts\ServiceRegistryInterface;
use App\Services\Gateway\Exceptions\ServiceNotFoundException;
use Illuminate\Contracts\Config\Repository as Config;

class ConfigServiceRegistry implements ServiceRegistryInterface
{
    public function __construct(protected Config $config) {}

    public function getBaseUrl(string $service): string
    {
        $key = $service . '_service';
        $url = $this->config->get("services.{$key}.base_url");

        if (empty($url)) {
            throw new ServiceNotFoundException("Service '{$service}' not configured.");
        }

        return rtrim($url, '/');
    }
}