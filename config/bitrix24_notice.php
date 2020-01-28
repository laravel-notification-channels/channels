<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ID the user on whose behalf notifications are sent
    |--------------------------------------------------------------------------
    |
    | In Bitrix24, when working with webhook, messages are created on behalf of a specific user whose id should be passed
    | Attention! the user id is closely related to the token, since the webhack is created under a specific user
    |
    */
    'fromUserId' => null,

    /*
    | OAuth-token of webhook
    |
    */
    'token' => '',

    /*
    | Your company's subdomain
    |
    | An example is given for the company https://example.bitrix24.ru/
    |
     */
    'domain' => 'example',
];