<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class AppBSignClient
{
    public function getSignedUrl(string $routeName, array $params = [], int $ttlMinutes = 120): string
    {
        $endpoint = config('services.app_b.sign_endpoint');
        $token    = config('services.app_b.sign_bearer');

        $response = Http::withToken($token)
            ->acceptJson()
            ->post($endpoint, [
                'route_name' => $routeName,
                'params'     => $params,
                'ttl_minutes'=> $ttlMinutes,
            ]);

        if (!$response->successful()) {
            throw new RuntimeException('App B signing failed: ' . $response->status() . ' ' . $response->body());
        }

        $url = $response->json('url');
        if (!$url) {
            throw new RuntimeException('App B did not return a url.');
        }

        return $url;
    }
}
