<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | This configuration is required and used to authorize and authenticate
    | towards the other end-points of the API. Get an API Key from Arkesel
    | dashboard at: https://sms.arkesel.com/user/sms-api/info
    |
    */

    'api_key' => env('ARKESEL_SMS_API_KEY'),


    /*
    |--------------------------------------------------------------------------
    | SMS API Version
    |--------------------------------------------------------------------------
    |
    | Specify which API version you want. If you prefer the SMS V1 API, update
    | this option to `v2` and update the `sms_url` config option to
    | 'https://sms.arkesel.com/sms/api?action=send-sms`
    |
    */

    'api_version' => env('ARKESEL_API_VERSION', 'v2'),


    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | This is the base URL used to send SMS. Defaults to the SMS V2 API endpoint.
    | Change this if you are using the SMS V1 API
    |
    */
    'sms_url' => env('ARKESEL_SMS_URL', 'https://sms.arkesel.com/api/v2/sms/send'),


    /*
    |--------------------------------------------------------------------------
    | Sender
    |--------------------------------------------------------------------------
    |
    | This is the name or number that identifies the sender of an SMS message.
    | Note that this field should be 11 characters max including space.
    | Anything more than that will result in your messages failing.
    |
    */

    'sms_sender' => env('ARKESEL_SMS_SENDER', config('app.name')),


    /*
    |--------------------------------------------------------------------------
    | Callback URL
    |--------------------------------------------------------------------------
    |
    | A URL that will be called to notify you about the status of the message
    | in the SMS V2 API. It must be a valid URL. This callback will receive
    | 2 query parameters: a unique `sms_id`(UUID) and the message `status`.
    |
    */

    'sms_callback_url' => env('ARKESEL_SMS_CALLBACK_URL'),


    /*
    |--------------------------------------------------------------------------
    | Sandbox
    |--------------------------------------------------------------------------
    |
    | A URL that will be called to notify you about the status of the message to
    | a particular number. It must be a valid URL. This callback will receive
    | 2 query parameters: a unique `sms_id`(UUID) and the message `status`.
    |
    */

    'sms_sandbox' => env('ARKESEL_SMS_SANDBOX', true),
];
