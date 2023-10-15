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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'digiflazz' => [
        'username' => env('DIGIFLAZZ_USERNAME'),
        'secret_key' => env('DIGIFLAZZ_SECRET_KEY'),
        'base_url' => env('DIGIFLAZZ_BASE_URL'),
    ],

    'gopay' => [
        'nomor' => env('GOJEK_PHONE'),
        'pin' => env('GOJEK_PIN')
    ],

    'ovopay' => [
        'nomor' => env('OVO_PHONE'),
        'pin' => env('OVO_PIN')
    ],
];
