<?php

return [

    'plans' => [
        'free' => [
            'name' => 'Free',
            'price_idr' => 0,
            'limit' => (int) env('UPLOAD_LIMIT_FREE', 3),
            'max_mb' => (int) env('UPLOAD_MAX_FILE_MB_FREE', 200),
            'features' => [
                '5 Analysis Videos',
                '10 Minutes per Video',
                'Standard Match Analysis',
                '2GB of Storages',
            ],
            'tone' => '#e6f9ff',
        ],



        'plus' => [
            'name' => 'Plus',
            'price_idr' => 129000,
            'limit' => (int) env('UPLOAD_LIMIT_PLUS', 200),
            'max_mb' => (int) env('UPLOAD_MAX_FILE_MB_PLUS', 2048),
            'features' => [
                '15 Videos per Month',
                '15 Minutes per Video',
                'Advanced Match Analysis',
                '5GB of Storage',
                'Skill-Based Matchmaking',
                'Advanced Profile Customization',
            ],
            'tone' => '#eefcc8',
        ],

         'pro' => [
            'name' => 'Pro',
            'price_idr' => 299000,
            'limit' => (int) env('UPLOAD_LIMIT_PRO', 50),
            'max_mb' => (int) env('UPLOAD_MAX_FILE_MB_PRO', 1024),
            'features' => [
                '40 Videos per Month',
                '20 Minutes per Video',
                'Pro-Level Match Analysis',
                '25GB of Storage',
                'Skill-Based Matchmaking',
                'Advanced Profile Customization',
                'AI-Generated Highlights',
                'AI-Coach Access',
                'Premium Profile Badge',
            ],
            'tone' => '#f2f6ff',
        ],
    ],
];
