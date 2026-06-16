<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class GatewayController extends Controller
{
    /**
     * Generic proxy for all microservices.
     */
    public function proxy(Request $request, string $service, ?string $path = '')
    {
        // 1. Resolve the service base URL from config
        $serviceConfigKey = $service . '_service';  // e.g., 'user_service'
        $baseUrl = config("services.{$serviceConfigKey}.base_url");

        if (!$baseUrl) {
            return response()->json([
                'error'   => 'Service not found',
                'message' => "No configuration for service '{$service}'",
            ], 404);
        }

        // 2. Build the full downstream URL
        //    The original request URI is /gateway/{service}/{path}
        //    We forward to {baseUrl}/{path} (the service itself decides the path)
        $downstreamUrl = rtrim($baseUrl, '/') . '/' . ltrim($path, '/');

        // 3. Prepare the request to forward
        try {
            // Create the HTTP client instance
            $http = Http::withHeaders(
                // Forward all headers except Host (to avoid conflicts)
                collect($request->headers->all())
                    ->except('host')
                    ->mapWithKeys(fn($value, $key) => [$key => $value[0]])
                    ->toArray()
            );

            // Forward the Authorization header if present (already included in headers)
            // Also forward other relevant headers (Accept, Content-Type, etc.)

            // Get the request body as raw content (supports JSON, form data, files)
            // For simplicity, we use $request->all() for form data or JSON.
            // For file uploads, you'd need to use multipart, but we can handle it generically.
            $body = $request->all();

            // Choose the HTTP method dynamically
            $method = strtolower($request->method());

            // Forward the request
            $response = $http->$method($downstreamUrl, $body);

            // 4. Return the response from the downstream service
            return response()->json($response->json(), $response->status())
                ->withHeaders($response->headers()); // forward headers (Content-Type, etc.)

        } catch (RequestException $e) {
            // Downstream service is unreachable or times out
            return response()->json([
                'error'   => 'Service unavailable',
                'message' => $e->getMessage(),
            ], 503);
        }
    }
}