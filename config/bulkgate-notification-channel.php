<?php

return [
    // How to get API access data: https://help.bulkgate.com/docs/en/api-administration.html#how-do-i-get-api-access-data
    'app_id' => env('BULKGATE_APP_ID'),
    'app_token' => env('BULKGATE_APP_TOKEN'),
    'send_unicode' => env('BULKGATE_SEND_UNICODE', false),

    // If you enter phone numbers in a national format, the system does not know where to send the messages.
    // Hence, unless the international prefix is explicitly filled in, you can define the default country
    // for the sender type to which the messages will be routed.
    // Via BulkGate\Sms\Country
    'default_country' => BulkGate\Sms\Country::UNITED_KINGDOM,
    // Or ISO 3166-1 alpha-2
    // 'default_country' => 'gb',

    // Choose the sender type to which the messages will be routed.
    // Available options:
    // -----------------
    // BulkGate\Sms\SenderSettings\Gate::GATE_SYSTEM_NUMBER
    // BulkGate\Sms\SenderSettings\Gate::GATE_SHORT_CODE
    // BulkGate\Sms\SenderSettings\Gate::GATE_TEXT_SENDER
    // BulkGate\Sms\SenderSettings\Gate::GATE_OWN_NUMBER
    // BulkGate\Sms\SenderSettings\Gate::GATE_MOBILE_CONNECT
    // See https://help.bulkgate.com/docs/en/php-sdk-sender-settings.html for more details
    'sender_type' => BulkGate\Sms\SenderSettings\Gate::GATE_SYSTEM_NUMBER,

    // Sender ID is required if sender_type is GATE_TEXT_SENDER or GATE_OWN_NUMBER
    // The maximal length of $value in case of the text sender ID is 11 characters of the English alphabet.
    // If you choose own number or mobile sender type it is necessary to verify on BulkGate Portal the number first.
    'sender_id' => env('BULKGATE_SENDER_ID', ''),
];
