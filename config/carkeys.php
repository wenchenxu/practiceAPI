<?php

return [
    'api' => [
        'base_url' => env('CAR_KEY_API_BASE_URL', 'https://saas.fhgreat.com'),
        'token_expiry' => (int) env('CAR_KEY_API_TOKEN_EXPIRY', 86400),
        'endpoints' => [
            // 'auth' => env('CAR_KEY_API_AUTH_URL'),
            'token' => env('CAR_KEY_API_TOKEN_ENDPOINT'), 
            'keyControl' => env('CAR_KEY_API_LOCK_ENDPOINT'),
            'keyCoordinates' => env('CAR_KEY_API_COORDINATE_ENDPOINT'),
            'keyInfo' => env('CAR_KEY_API_INFO_ENDPOINT'),
            'clientId' => env('CAR_KEY_API_CLIENT_ID'),
            'tenantId' => env('CAR_KEY_API_TENANT_ID'),
        ],
        'timeout' => (int) 15,
    ],
];