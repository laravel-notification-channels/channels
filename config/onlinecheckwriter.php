<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OnlineCheckWriter API Key
    |--------------------------------------------------------------------------
    |
    | Your OnlineCheckWriter API key (Bearer token) for authentication.
    | This should be your LIVE API key, not the sandbox key.
    |
    */
    'api_key' => env('ONLINECHECKWRITER_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the OnlineCheckWriter API.
    | - Test: https://test.onlinecheckwriter.com/api/v3
    | - Live: https://api.onlinecheckwriter.com/api/v3
    |
    */
    'base_url' => env('ONLINECHECKWRITER_BASE_URL', 'https://api.onlinecheckwriter.com/api/v3'),

    /*
    |--------------------------------------------------------------------------
    | Default Sender Information
    |--------------------------------------------------------------------------
    |
    | Default sender (fromAddress) details to use when sending mail if not
    | specified in the notification.
    |
    */
    'default_sender' => [
        'name' => env('ONLINECHECKWRITER_SENDER_NAME', ''),
        'company' => env('ONLINECHECKWRITER_SENDER_COMPANY', ''),
        'address_line_1' => env('ONLINECHECKWRITER_SENDER_ADDRESS_1', ''),
        'address_line_2' => env('ONLINECHECKWRITER_SENDER_ADDRESS_2', ''),
        'city' => env('ONLINECHECKWRITER_SENDER_CITY', ''),
        'state' => env('ONLINECHECKWRITER_SENDER_STATE', ''),
        'zip' => env('ONLINECHECKWRITER_SENDER_ZIP', ''),
        'phone' => env('ONLINECHECKWRITER_SENDER_PHONE', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Shipping Type
    |--------------------------------------------------------------------------
    |
    | Default shipping type ID for document mailing.
    | Common values: 1 = Standard, 2 = Express, 3 = Priority
    |
    */
    'default_shipping_type' => env('ONLINECHECKWRITER_SHIPPING_TYPE', 3),

    /*
    |--------------------------------------------------------------------------
    | Default Bank Account ID
    |--------------------------------------------------------------------------
    |
    | The default bank account ID to use for check printing if not specified
    | in the notification.
    |
    */
    'default_bank_account_id' => env('ONLINECHECKWRITER_BANK_ACCOUNT_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Timeout
    |--------------------------------------------------------------------------
    |
    | The number of seconds to wait for API responses.
    |
    */
    'timeout' => env('ONLINECHECKWRITER_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Retry Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for retry logic on failed requests.
    |
    */
    'retry' => [
        'times' => 3,
        'sleep' => 100, // milliseconds
    ],
];
