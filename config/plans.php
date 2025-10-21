<?php

return [

    'plans' => [
        'free' => [
            'name' => 'Free',
            'price_idr' => 0,
            'limit' => (int) env('UPLOAD_LIMIT_FREE', 3),
            'max_mb' => (int) env('UPLOAD_MAX_FILE_MB_FREE', 200),
            'features' => [
                'Up to ' . env('UPLOAD_LIMIT_FREE', 3) . ' video analytics',
                'Dashboard metrics',
                'AI mapping',
                'Communities sharing',
            ],
            'tone' => '#e6f9ff',
        ],



        'plus' => [
            'name' => 'Plus',
            'price_idr' => 129000,
            'limit' => (int) env('UPLOAD_LIMIT_PLUS', 200),
            'max_mb' => (int) env('UPLOAD_MAX_FILE_MB_PLUS', 2048),
            'features' => [
                'Up to ' . env('UPLOAD_LIMIT_PLUS', 200) . ' video analytics',
                'Dashboard metrics',
                'AI mapping',
                'Priority processing',
                'Communities sharing',
            ],
            'tone' => '#eefcc8',
        ],

         'pro' => [
            'name' => 'Pro',
            'price_idr' => 299000,
            'limit' => (int) env('UPLOAD_LIMIT_PRO', 50),
            'max_mb' => (int) env('UPLOAD_MAX_FILE_MB_PRO', 1024),
            'features' => [
                'Up to ' . env('UPLOAD_LIMIT_PRO', 50) . ' video analytics',

                'Dashboard metrics',
                'AI mapping',
                'Unlocked new feature',
                'Custom video analytics',
                'Unlimited storage',
                'Heatmap systems',
                'Player report',
                'Communities sharing',
            ],
            'tone' => '#f2f6ff',
        ],
    ],
];
