<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SMS "From" Number
    |--------------------------------------------------------------------------
    |
    | This configuration option defines the phone number that will be used as
    | the "from" number for all outgoing text messages. You should provide
    | the number you have already reserved within your ClickSend dashboard.
    |
    */

    'sms_from' => env('CLICK_SEND_FROM'),

    /*
    |--------------------------------------------------------------------------
    | API Credentials
    |--------------------------------------------------------------------------
    |
    | The following configuration options contain your API credentials, which
    | may be accessed from your ClickSend dashboard. These credentials may be
    | used to authenticate with the ClickSend API to allow sending messages.
    |
    */

    'api_username' => env('CLICK_SEND_USERNAME'),
    'api_key' => env('CLICK_SEND_KEY'),

    // OR

    'account_username' => env('CLICK_SEND_ACCOUNT_USERNAME'),
    'account_password' => env('CLICK_SEND_ACCOUNT_PASSWORD'),

];
