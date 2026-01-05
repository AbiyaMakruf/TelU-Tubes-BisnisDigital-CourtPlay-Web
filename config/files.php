<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Upload Configuration
    |--------------------------------------------------------------------------
    */

    'upload' => [
        'allowed_mimes' => ['mp4'],

        'plans' => [
            'free' => [
                'limit' => env('UPLOAD_LIMIT_FREE', 3),
                'max_file_mb' => env('UPLOAD_MAX_FILE_MB_FREE', 200),
            ],
            'plus' => [
                'limit' => env('UPLOAD_LIMIT_PLUS', 50),
                'max_file_mb' => env('UPLOAD_MAX_FILE_MB_PLUS', 1024),
            ],
            'pro' => [
                'limit' => env('UPLOAD_LIMIT_PRO', 200),
                'max_file_mb' => env('UPLOAD_MAX_FILE_MB_PRO', 2048),
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Profile Configuration
    |--------------------------------------------------------------------------
    */

    'profile' => [
        'max_image_mb' => env('PROFILE_MAX_IMAGE_MB', 2),
        'storage_disk' => env('PROFILE_STORAGE_DISK', 'public'),
        'allowed_mimes' => explode(',', env('PROFILE_ALLOWED_MIMES', 'jpg,jpeg,png,webp')),
    ],

    /*
    |--------------------------------------------------------------------------
    | News Configuration
    |--------------------------------------------------------------------------
    */

    'news' => [
        'max_image_mb' => env('NEWS_MAX_IMAGE_MB', 5),
        'allowed_mimes' => explode(',', env('NEWS_ALLOWED_MIMES', 'jpg,jpeg,png,webp')),
    ],

];
