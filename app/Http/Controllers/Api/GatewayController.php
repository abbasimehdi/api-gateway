<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Gateway\GatewayProxy;
use App\Services\Gateway\ProxyRequest;
use App\Services\Gateway\Exceptions\ServiceNotFoundException;
use App\Services\Gateway\Exceptions\ServiceUnavailableException;
use Illuminate\Http\Request;

class GatewayController extends Controller
{
    public function __construct(protected GatewayProxy $proxy) {}

    public function proxy(Request $request, string $service, ?string $path = '')
    {
        try {
            $proxyRequest = ProxyRequest::fromIlluminateRequest($request, $path);
            $result = $this->proxy->forward($proxyRequest, $service);

            return response()->json($result['body'], $result['status'])
                ->withHeaders($result['headers']);
        } catch (ServiceNotFoundException $e) {
            return response()->json([
                'error'   => 'Service not found',
                'message' => $e->getMessage(),
            ], 404);
        } catch (ServiceUnavailableException $e) {
            return response()->json([
                'error'   => 'Service unavailable',
                'message' => $e->getMessage(),
            ], 503);
        }
    }
}