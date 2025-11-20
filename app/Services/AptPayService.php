<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AptPayService
{
    public static function callApi(string $endpoint, array $payload)
    {
        $apiKey    = config('services.aptpay.api_key');
        $secretKey = config('services.aptpay.secret_key');
        $baseUrl   = config('services.aptpay.base_url');
        $jsonBody  = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $bodyHash  = hash_hmac('sha512', $jsonBody, $secretKey);

        $response = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'AptPayApiKey'  => $apiKey,
            'body-hash'     => $bodyHash,
        ])->withBody($jsonBody, 'application/json')
          ->post($baseUrl . $endpoint);

        return $response;
    }
}
