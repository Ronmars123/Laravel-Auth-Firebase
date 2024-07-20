<?php

declare(strict_types=1);

return [
    'default' => env('FIREBASE_PROJECT', ''),

    'projects' => [
        'app' => [
            'credentials' => storage_path(env('FIREBASE_CREDENTIALS')),
            'database' => [
                'url' => env('FIREBASE_DATABASE_URL'),
            ],
            'auth' => [
                'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
            ],
        ],
    ],
];
