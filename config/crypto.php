<?php

return [
    'api' => [
        'base_url' => env('CRYPTO_API_URL', 'https://api.coingecko.com/api/v3'),
        'currency' => env('CRYPTO_CURRENCY', 'usd'),
        'per_page' => env('CRYPTO_PER_PAGE', 250),
    ],

    'cache' => [
        'ttl_minutes' => env('CRYPTO_CACHE_TTL_MINUTES', 5),
        'key' => env('CRYPTO_CACHE_KEY', 'crypto_market_full_dataset'),
    ],
];
