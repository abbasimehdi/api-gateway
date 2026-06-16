<?php

namespace App\Services\Gateway\Contracts;

interface ServiceRegistryInterface
{
    /**
     * Get the base URL of the given service.
     *
     * @throws ServiceNotFoundException
     */
    public function getBaseUrl(string $service): string;
}