<?php

return [
    'interests' => [
        /*
         * Supported: "file", "database"
         */
        'driver' => env('EXPONENT_PUSH_NOTIFICATION_INTERESTS_STORAGE_DRIVER', 'file'),

        'database' => [
            'table_name' => 'exponent_push_notification_interests',
        ],
    ],
];
