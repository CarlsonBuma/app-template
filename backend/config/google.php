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

    'api_key' => env('GOOGLE_API_KEY', ''),
    'project_id' => env('GOOGLE_PROJECT_ID', 'x-12345'),
    'key_file_path' => env('GOOGLE_CLOUD_KEY_FILE', 'keys/some-file.json'),
    'bucket_images' => env('GOOGLE_STORAGE_BUCKET_IMAGES', 'bucket-image'),
];
