<?php

return [
    'coingecko' => [
        'default' => 'CoinGecko API Error',
        'http' => [
            'bad_request' => 'Bad request - invalid parameters',
            'unauthorized' => 'Unauthorized - authentication required',
            'forbidden' => 'Forbidden - access denied',
            'not_found' => 'Resource not found',
            'rate_limit' => 'Rate limit exceeded. Try again later.',
            'server_error' => 'Internal server error',
        ],
        'api_key_missing' => 'API key missing or incorrect',
        'pro_only_endpoint' => 'This request is limited to Pro API subscribers',
        'cloudflare' => 'Access denied by CDN firewall',
        'connection_failed' => 'Failed to connect to CoinGecko API',
        'malformed_response' => [
            'default' => 'Invalid or malformed response received',
            'not_array' => 'Response is not an array',
            'parse_failed' => 'Failed to parse JSON response',
        ],
        'unexpected_status' => 'Unexpected status code: :status',
    ],
]; 