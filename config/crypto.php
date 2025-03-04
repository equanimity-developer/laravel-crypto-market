<?php

return [
    'api' => [
        'base_url' => env('COINGECKO_API_URL', 'https://api.coingecko.com/api/v3'),
    ],

    'cache' => [
        'ttl_minutes' => env('CRYPTO_CACHE_TTL_MINUTES', 5),
    ],
];
