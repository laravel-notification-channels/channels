<?php

return [
    'username' => env('TEXTLOCAL_USERNAME'),
    'password' => env('TEXTLOCAL_PASSWORD'),
    'hash' => env('TEXTLOCAL_HASH'),
    'api_key' => env('TEXTLOCAL_API_KEY'),
    'sender' => env('TEXTLOCAL_SENDER', 'DOCONL'),
    'request_urls' => [
        'IN' => 'https://api.textlocal.in/',
        'UK' => 'https://api.txtlocal.com/',
    ],
    'country' => env('TEXTLOCAL_COUNTRY', 'IN'),
    /*
    // a sample way to organize custom config when using mutliple on demand config
    // uncomment this for that purpose or feel free to use your own implementations
    'UK' => [
        'username' => env('TEXTLOCAL_USERNAME_UK'),
        'password' => env('TEXTLOCAL_PASSWORD_UK'),
        'hash' => env('TEXTLOCAL_HASH_UK'),
        'api_key' => env('TEXTLOCAL_API_KEY_UK'),
        'sender' => env('TEXTLOCAL_SENDER_UK', 'DOCONL'),
        'country' => env('TEXTLOCAL_COUNTRY_UK', 'UK'),
    ],
    */
];
