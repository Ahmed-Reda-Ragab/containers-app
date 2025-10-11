<?php
return [
    'client_id' => env('SMARTCAR_CLIENT_ID'),
    'client_secret' => env('SMARTCAR_CLIENT_SECRET'),
    'redirect_uri' => env('SMARTCAR_REDIRECT_URI'),
    'mode' => env('SMARTCAR_MODE', 'simulated'), // or 'live'
    'base_url' => 'https://api.smartcar.com/v2.0',
    'auth_url' => 'https://auth.smartcar.com/oauth',
    'connect_url' => 'https://connect.smartcar.com/oauth/authorize',
    'management_token' => env('SMARTCAR_MANAGEMENT_TOKEN'),
    'webhook_url' => env('SMARTCAR_WEBHOOK_URL'),
];
