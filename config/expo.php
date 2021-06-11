<?php

return [
    /**
     * Additional Security
     * https://docs.expo.io/push-notifications/sending-notifications/#additional-security.
     *
     * If you have this enabled, please set your access token below. If you have not enabled this feature
     * you may leave this as null.
     */
    'access_token' => env('EXPO_ACCESS_TOKEN', null),
];
