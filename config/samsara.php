<?php

return [
    'base_url' => env('SAMSARA_BASE_URL', 'https://api.samsara.com'),
    'api_key' => env('SAMSARA_API_KEY'),
    // Or whichever auth mechanism Samsara uses (bearer token, API key, etc.)
    'timeout' => env('SAMSARA_TIMEOUT', 30),
];
