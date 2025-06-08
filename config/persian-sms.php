    <?php

    return [

        /*
        |--------------------------------------------------------------------------
        | Default SMS Driver
        |--------------------------------------------------------------------------
        |
        | This option controls the default SMS driver that will be used when sending
        | notifications. By default, this is set to 'ippanel', but you can
        | change it to any of the drivers configured below.
        |
        | Supported: "ippanel", "kavenegar", ... (add more as you implement them)
        |
        */

        'default_driver' => env('PERSIAN_SMS_DRIVER', 'ippanel'),

        /*
        |--------------------------------------------------------------------------
        | SMS Driver Configurations
        |--------------------------------------------------------------------------
        |
        | Here you may configure all of the SMS drivers used by your application.
        | You are free to add more drivers as needed. Each driver requires its
        | own set of configuration options.
        |
        */

        'drivers' => [

            'ippanel' => [
                'api_key'       => env('IPPANEL_API_KEY'),
                'sender_number' => env('IPPANEL_SENDER_NUMBER'), // Your default line number
                // 'api_url'    => 'https://api2.ippanel.com/api/v1', // Optional: if you want to override
            ],

            'kavenegar' => [ // Example for a future driver
                'api_key'       => env('KAVENEGAR_API_KEY'),
                'sender_number' => env('KAVENEGAR_SENDER_NUMBER'),
                // ... other kavenegar specific settings
            ],

            // Add other drivers here...

        ],

        /*
        |--------------------------------------------------------------------------
        | Guzzle HTTP Client Options
        |--------------------------------------------------------------------------
        |
        | You can pass any Guzzle-specific request options here.
        | For example, you might want to set a timeout.
        | See Guzzle documentation for all available options.
        |
        */
        'guzzle' => [
            'timeout' => 10.0, // Request timeout in seconds
            // 'connect_timeout' => 5.0,
            // 'verify' => true, // SSL certificate verification
            // 'proxy' => 'http://your-proxy.com:port',
        ],

    ];
    