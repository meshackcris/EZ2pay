<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'aptpay' => [
    'api_key'    => env('APTPAY_API_KEY'),
    'secret_key' => env('APTPAY_SECRET_KEY'),
    'api_token'  => env('APTPAY_API_TOKEN'),
    'base_url'   => env('APTPAY_API_URL'),
    ],
    'app_b' => [
    'sign_endpoint' => env('APP_B_SIGN_ENDPOINT'),
    'sign_bearer'   => env('APP_B_SIGN_BEARER'),
    ],

];
