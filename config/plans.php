<?php

return [

    'plans' => [
        'free' => [
            'name' => 'Free',
            'price_idr' => 0,
            'limit' => (int) env('UPLOAD_LIMIT_FREE', 2),
            'max_mb' => (int) env('UPLOAD_MAX_FILE_MB_FREE', 200),
            'features' => [
                '2 Analysis Videos',
                '5 Minutes per Video',
                'Standard Match Analysis',
                '2GB of Storages',
                'AI-Generated Highlights',
            ],
            'tone' => '#e6f9ff',
        ],

        'starter' => [
            'name' => 'Starter',
            'price_idr' => 20000,
            'limit' => (int) env('UPLOAD_LIMIT_STARTER', 5),
            'max_mb' => (int) env('UPLOAD_MAX_FILE_MB_STARTER', 300),
            'is_one_time' => true, // One-time purchase, not monthly
            'features' => [
                '5 Analysis Videos',
                '10 Minutes per Video',
                'Standard Match Analysis',
                '2GB Storage',
                'Basic Analytics Dashboard',
                'AI-Generated Highlights',
            ],
            'tone' => '#fff4e6',
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
                'AI-Generated Highlights',
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
