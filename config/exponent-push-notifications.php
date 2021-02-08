<?php

/**
 * Here you may define the configuration for the expo-notifications-driver.
 * The expo-notifications-driver can guide the sdk to use `database` or `file` repositories.
 * The database repository uses the same configuration for the database in your Laravel app.
 */

return [
    'middleware' => [
        //'auth:sanctum', //<- Use only this middleware if you're using Sanctum
        'auth:api',
        'bindings',
    ],
    'debug' => env('EXPONENT_PUSH_NOTIFICATION_DEBUG', true),

    'interests' => [
        'driver' => env('EXPONENT_PUSH_NOTIFICATION_INTERESTS_STORAGE_DRIVER', 'file'),

        'database' => [
            'events' => [],

            'table_name' => 'exponent_push_notification_interests',
        ],
    ],
];
