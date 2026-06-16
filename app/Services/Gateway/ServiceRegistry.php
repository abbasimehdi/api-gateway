<?php

namespace App\Services\Gateway;

use Illuminate\Contracts\Config\Repository as Config;
use App\Services\Gateway\Exceptions\ServiceNotFoundException;

class ServiceRegistry
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