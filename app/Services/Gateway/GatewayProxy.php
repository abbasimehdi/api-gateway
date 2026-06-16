<?php

namespace App\Services\Gateway;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use App\Services\Gateway\Exceptions\ServiceNotFoundException;
use App\Services\Gateway\Exceptions\ServiceUnavailableException;

class GatewayProxy
{
    public function __construct(protected ServiceRegistry $registry) {}

    public function forward(ProxyRequest $request, string $service): array
    {
        $baseUrl = $this->registry->getBaseUrl($service);
        $url = $baseUrl . '/' . ltrim($request->path, '/');

        try {
            $http = Http::withHeaders($request->headers)
                ->timeout(30)
                ->retry(2, 100);

            $response = $http->{$request->method}($url, $request->body);

            $body = $response->json();
            if (is_null($body)) {
                $body = ['raw' => $response->body()];
            }

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