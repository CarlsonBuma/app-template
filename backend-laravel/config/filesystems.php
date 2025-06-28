<?php

return [

    /**
     * Google Cloud Storage
     * https://github.com/spatie/laravel-google-cloud-storage
     * 
     */
    'gcs' => [
        'key_file_path' => base_path(env('GOOGLE_CLOUD_KEY_FILE', 'keys/google-cloud-services.json')),
        'project_id' => env('GOOGLE_PROJECT_ID', 'your-project-id'),
        'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET', 'localhost'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'backup_only_db' => [
            'driver' => 'local',
            'root' => storage_path('backups/only-db'),
        ],

        'backup_full_db' => [
            'driver' => 'local',
            'root' => storage_path('backups/full-db'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('CLIENT_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        'user' => [
            'driver' => 'local',
            'root' => storage_path('app/public/user'),
            'url' => env('CLIENT_URL').'/storage/user',
            'visibility' => 'public',
            'throw' => false,
        ],

        'cockpit' => [
            'driver' => 'local',
            'root' => storage_path('app/public/cockpit'),
            'url' => env('CLIENT_URL').'/storage/cockpit',
            'visibility' => 'public',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
