<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'payment/notification'],

    'allowed_methods' => ['*'],

    'allowed_origins' => explode(',', env('FRONTEND_URLS', 'http://localhost:5173')),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];