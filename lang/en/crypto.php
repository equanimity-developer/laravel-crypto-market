<?php

return [
    'page_title' => 'Crypto Market',
    'title' => 'Cryptocurrency Market',
    'loading' => 'Loading cryptocurrency data...',
    'error' => 'Unable to fetch cryptocurrency data. Please try again later.',

    'table' => [
        'rank' => 'Rank',
        'name' => 'Name',
        'price' => 'Price',
        'change_24h' => '24h Change',
        'market_cap' => 'Market Cap',
    ],

    'filters' => [
        'title' => 'Filters',
        'apply' => 'Apply Filters',
        'reset' => 'Reset',
        'search' => 'Search',
        'min_price' => 'Min Price ($)',
        'max_price' => 'Max Price ($)',
        'search_placeholder' => 'Bitcoin, ETH...',
        'min_placeholder' => '0',
        'max_placeholder' => '1000',
    ],

    'logs' => [
        'fetching_data' => 'Fetching fresh market data from CoinGecko API',
        'api_error' => 'CoinGecko API error',
        'fetch_error' => 'Failed to fetch market data:',
        'error_overview' => 'Error fetching market overview',
    ],

    'languages' => [
        'en' => 'English',
        'pl' => 'Polish',
    ],

    'sorting' => [
        'asc' => 'Ascending',
        'desc' => 'Descending',
    ],

    'pagination' => [
        'showing' => 'Showing',
        'to' => 'to',
        'of' => 'of',
        'results' => 'results',
        'previous' => 'Previous',
        'next' => 'Next',
    ],

    'messages' => [
        'cache_cleared' => 'Cache cleared successfully. Showing fresh market data.',
    ],

    'controls' => [
        'refresh' => 'Refresh Data',
    ],
];
