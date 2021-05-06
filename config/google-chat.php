<?php

return [
    /**
     * Default Space Webhook Url.
     *
     * This key defines the default space where Google Chat messages will be posted to. Of
     * course, individual messages can be routed to a specific space using the
     * `GoogleChatMessage::to()` method.
     *
     * If your application does not need a default space, you can leave this value as null.
     */
    'space' => env('GOOGLE_CHAT_DEFAULT_SPACE', null),

    /**
     * Additional Spaces.
     *
     * This key defines additional spaces which can be used as the argument in the
     * `GoogleChatMessage::to({key})` method. For example, using the 'sales_team'
     * example key below, we can direct an individual notification to that
     * endpoint like:
     *
     * ````
     * GoogleChatMessage::create('My Message')->to('sales_team');
     * ````
     */
    'spaces' => [
        // 'sales_team' => 'https://chat.googleapis.com/...'
    ],
];
