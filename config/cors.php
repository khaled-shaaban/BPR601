<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | The API is consumed by the Nuxt SPA using bearer-token authentication
    | (no cookies), so a wildcard origin is safe here. Tighten `allowed_origins`
    | to the deployed client origin(s) in production.
    |
    */

    'paths' => ['api/*', 'broadcasting/auth', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Lock to your deployed client origin(s) in production via FRONTEND_URL
    // (comma-separated). Falls back to '*' for local development.
    'allowed_origins' => env('FRONTEND_URL') ? explode(',', env('FRONTEND_URL')) : ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
