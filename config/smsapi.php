<?php

return [
    'auth' => [
        /*
        |----------------------------------------------------------------------
        | Authentication methods
        |----------------------------------------------------------------------
        |
        | Supported: "token", "password".
        |
        */
        'method' => 'token',

        'credentials' => [
            'token' => env('SMSAPI_AUTH_TOKEN'),

            // 'username' => env('SMSAPI_AUTH_USERNAME'),
            // 'password' => env('SMSAPI_AUTH_PASSWORD'), // Hashed by MD5
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default options
    |--------------------------------------------------------------------------
    |
    | These options will be set if user does not provide their own in code.
    |
    */
    'defaults' => [
        /*
        |----------------------------------------------------------------------
        | Common default options
        |----------------------------------------------------------------------
        |
        | Setting options here will take precedence
        | over those set in dashboard.
        |
        | Common supported options: "notify_url", "partner", "test".
        |
        */
        'common' => [
            // 'notify_url' => env('SMSAPI_NOTIFY_URL'),
            // 'partner' => env('SMSAPI_PARTNER'),
            // 'test' => env('SMSAPI_TEST', true),
        ],

        /*
        |----------------------------------------------------------------------
        | SMS default options
        |----------------------------------------------------------------------
        |
        | Setting common options here will take precedence
        | over those set in common section.
        |
        | SMS only supported options: "from", "fast", "flash", "encoding",
        | "normalize", "nounicode", "single".
        |
        */
        'sms' => [
            // 'from' => env('SMSAPI_FROM'),
            // 'fast' => false,
            // 'flash' => false,
            // 'encoding' => 'utf-8',
            // 'normalize' => false,
            // 'nounicode' => false,
            // 'single' => false,
        ],

        /*
        |----------------------------------------------------------------------
        | MMS default options
        |----------------------------------------------------------------------
        |
        | Setting common options here will take precedence
        | over those set in common section.
        |
        */
        'mms' => [
        ],

        /*
        |----------------------------------------------------------------------
        | VMS default options
        |----------------------------------------------------------------------
        |
        | Setting common options here will take precedence
        | over those set in common section.
        |
        | VMS only supported options: "from", "tries", "interval", "tts_lector",
        | "skip_gsm".
        |
        */
        'vms' => [
            // 'from' => env('SMSAPI_FROM'),
            // 'tries' => 2,
            // 'interval' => 300,
            // 'tts_lector' => 'Agnieszka',
            // 'skip_gsm' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Extra options
    |--------------------------------------------------------------------------
    |
    | For future compatibility feel free to provide here your own options.
    |
    */
    'extra' => [
    ],
];
