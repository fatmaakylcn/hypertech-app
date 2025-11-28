<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Hyper Teknoloji API credentials
    |--------------------------------------------------------------------------
    */
    'base_url' => env('HYPERTECH_API_BASE_URL', 'https://api.hyperteknoloji.com.tr'),
    'api_key' => env('HYPERTECH_API_KEY'),
    'api_token' => env('HYPERTECH_API_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Pagination defaults
    |--------------------------------------------------------------------------
    */
    'default_page_size' => env('HYPERTECH_PAGE_SIZE', 12),

    /*
    |--------------------------------------------------------------------------
    | Cache settings (seconds)
    |--------------------------------------------------------------------------
    */
    'cache_ttl' => env('HYPERTECH_CACHE_TTL', 300),
];

