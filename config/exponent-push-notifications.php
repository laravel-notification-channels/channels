<?php

/**
 * Here you may define the configuration for the expo-notifications-driver.
 * The expo-notifications-driver can guide the sdk to use `database` or `file` repositories.
 * The database repository uses the same configuration for the database in your Laravel app.
 */

return [
    'interests' => [
        'driver' => env('EXPONENT_PUSH_NOTIFICATION_INTERESTS_STORAGE_DRIVER', 'file'),

        'database' => [
            'table_name' => 'exponent_push_notification_interests',
        ],
    ],
];
