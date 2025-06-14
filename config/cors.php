<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration defines how your Laravel application handles
    | Cross-Origin Resource Sharing (CORS) requests. Customize the
    | settings below to match your development and production needs.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:3000',        // React
        'http://127.0.0.1:3000',
        'http://localhost:5000',        // Flutter
        'http://127.0.0.1:5000',
        'http://127.0.0.1:8000',
        'https://your-production-url.com', // Production domain
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,  // Enable if you use cookies or auth tokens
];
