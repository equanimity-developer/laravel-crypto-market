<?php

return [
    'api' => [
        'base_url' => env('CRYPTO_API_BASE_URL', 'https://api.coingecko.com/api/v3'),
        'currency' => env('CRYPTO_API_CURRENCY', 'usd'),
        'timeout' => (int)env('CRYPTO_API_TIMEOUT', 30),
        'retries' => (int)env('CRYPTO_API_RETRIES', 3),
        'retry_delay' => (int)env('CRYPTO_API_RETRY_DELAY', 1000),
    ],

    'cache' => [
        'ttl_minutes' => (int)env('CRYPTO_CACHE_TTL_MINUTES', 5),
        'key' => env('CRYPTO_CACHE_KEY', 'crypto_market_full_dataset'),
    ],
];
