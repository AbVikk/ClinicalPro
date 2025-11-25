<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Gemini API Key
    |--------------------------------------------------------------------------
    |
    | This key is used to authenticate with Google's Generative AI.
    |
    */
    'api_key' => env('GEMINI_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    */
    'base_url' => env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout (Seconds)
    |--------------------------------------------------------------------------
    */
    'request_timeout' => (int) env('GEMINI_REQUEST_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | We define the primary and fallback models here so we can change them
    | without touching the code.
    */
    'models' => [
        'primary' => env('GEMINI_MODEL', 'gemini-1.5-flash'),
        'fallback' => env('GEMINI_FALLBACK_MODEL', 'gemini-1.5-pro'),
    ],
];