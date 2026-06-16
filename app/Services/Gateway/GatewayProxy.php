<?php

namespace App\Services\Gateway;

use App\Services\Gateway\Contracts\ServiceRegistryInterface;
use App\Services\Gateway\Exceptions\ServiceUnavailableException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class GatewayProxy
{
    public function __construct(protected ServiceRegistryInterface $registry) {}

    public function forward(ProxyRequest $request, string $service): array
    {
        $baseUrl = $this->registry->getBaseUrl($service);
        $url = $baseUrl . '/' . ltrim($request->path, '/');

        try {
            $http = Http::withHeaders($request->headers)
                ->timeout(30)
                ->retry(2, 100);

            $response = $http->{$request->method}($url, $request->body);

            $body = $response->json() ?? ['raw' => $response->body()];

            return [
                'status'  => $response->status(),
                'headers' => $response->headers(),
                'body'    => $body,
            ];
        } catch (RequestException $e) {
            throw new ServiceUnavailableException(
                "Service '{$service}' is unavailable: " . $e->getMessage()
            );
        }
    }
}