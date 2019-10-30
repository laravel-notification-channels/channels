<?php 

return [
	'username'  => env('TEXTLOCAL_USERNAME'),
	'password'  => env('TEXTLOCAL_PASSWORD'),
	'hash'      => env('TEXTLOCAL_HASH'),
	'api_key'   => env('TEXTLOCAL_API_KEY'),
	'sender'    => env('TEXTLOCAL_SENDER'),
    'request_urls' => [
        'IN' => 'https://api.textlocal.in/',
        'UK' => 'https://api.txtlocal.com/'
    ],
    'country'   => env('TEXTLOCAL_COUNTRY', 'IN'),
];
