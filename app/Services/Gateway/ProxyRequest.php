<?php

namespace App\Services\Gateway;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProxyRequest
{
    public function __construct(
        public readonly string $method,
        public readonly array $headers,
        public readonly array $body,
        public readonly string $path,
    ) {}

    public static function fromIlluminateRequest(Request $request, string $path): self
    {
        $headers = collect($request->headers->all())
            ->except('host')
            ->mapWithKeys(fn($values, $key) => [$key => $values[0]])
            ->toArray();

        // Ensure we ask for JSON
        $headers['Accept'] = 'application/json';

        return new self(
            method: strtolower($request->method()),
            headers: $headers,
            body: $request->all(),
            path: $path,
        );
    }
}