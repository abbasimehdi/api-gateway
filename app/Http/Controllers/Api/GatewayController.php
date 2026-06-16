<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class GatewayController extends Controller
{
     public function getUser(Request $request)
    {
        try {
            $userServiceUrl = config('services.user_service.base_url');
            $response = Http::withHeaders([
            'Accept'        => 'application/json',
            ])->get($userServiceUrl . '/api/users');

            // 4. Return the response from User Service to React
            return response()->json($response->json(), $response->status());

        } catch (RequestException $e) {
            // User Service is unreachable
            return response()->json([
                'error' => 'User service unavailable',
                'message' => $e->getMessage(),
            ], 503);
        }
    }

    /**
     * Forward user creation request to the User Service.
     */
    public function createUser(Request $request)
    {
        // 1. (Optional) Authenticate the request using Laravel Passport
        //    Uncomment if you have Passport set up and want to protect this endpoint.
        //    The user must send a Bearer token.
        // $this->middleware('auth:api')->only('createUser');

        // 2. Get User Service URL from config (or service discovery)
        $userServiceUrl = config('services.user_service.base_url');

        // 3. Forward the request exactly as received
        try {
            $response = Http::withHeaders([
                // Forward the Authorization header if present (for downstream auth)
                'Authorization' => $request->header('Authorization', ''),
            ])->post($userServiceUrl . '/api/users', $request->all());

            // 4. Return the response from User Service to React
            return response()->json($response->json(), $response->status());

        } catch (RequestException $e) {
            // User Service is unreachable
            return response()->json([
                'error' => 'User service unavailable',
                'message' => $e->getMessage(),
            ], 503);
        }
    }
}