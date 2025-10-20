<?php

return [

    'usd_to_idr' => 16000,

    'plans' => [
        'free' => [
            'name' => 'Free',
            'price_usd' => 0,
            'users' => '1 user',
            'limit' => (int) env('UPLOAD_LIMIT_FREE', 3),
            'max_mb' => (int) env('UPLOAD_MAX_FILE_MB_FREE', 200),
            'features' => [
                'Up to ' . env('UPLOAD_LIMIT_FREE', 3) . ' video analytics',
                'Dashboard metrics',
                'AI mapping',
            ],
            'tone' => '#e6f9ff',
        ],

        'pro' => [
            'name' => 'Pro',
            'price_usd' => 49,
            'users' => '3 user',
            'limit' => (int) env('UPLOAD_LIMIT_PRO', 50),
            'max_mb' => (int) env('UPLOAD_MAX_FILE_MB_PRO', 1024),
            'features' => [
                'Up to ' . env('UPLOAD_LIMIT_PRO', 50) . ' video analytics',
                'Dashboard metrics',
                'AI mapping',
                'Priority processing',
            ],
            'tone' => '#f2f6ff',
        ],

        'plus' => [
            'name' => 'Plus',
            'price_usd' => 99,
            'users' => '5 user',
            'limit' => (int) env('UPLOAD_LIMIT_PLUS', 200),
            'max_mb' => (int) env('UPLOAD_MAX_FILE_MB_PLUS', 2048),
            'features' => [
                'Up to ' . env('UPLOAD_LIMIT_PLUS', 200) . ' video analytics',
                'Dashboard metrics',
                'AI mapping',
                'Unlocked new feature',
                'Custom video analytics',
                'Unlimited storage',
            ],
            'tone' => '#eefcc8',
        ],
    ],
];
