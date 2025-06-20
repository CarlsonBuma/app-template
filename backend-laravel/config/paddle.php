<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Paddle
    |--------------------------------------------------------------------------
    |
    | Here you may specify your OpenAI API Key and organization. This will be
    | used to authenticate with the OpenAI API - you can find your API key
    | and organization on your OpenAI dashboard, at https://openai.com.
    */
    'sandbox' => env('PADDLE_SANDBOX', true),
    'url' => env('PADDLE_API_URL', 'https://sandbox-api.paddle.com/'),
    'api_key' => env('PADDLE_API_KEY', ''),
    'webhook_secret' => env('PADDLE_WEBHOOK_SECRET', ''),
];
