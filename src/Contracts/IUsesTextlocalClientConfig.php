<?php

namespace NotificationChannels\Textlocal\Contracts;

interface IUsesTextlocalClientConfig
{
    /**
     * @param $notification
     * @return array [$username, $hash, $apiKey, $country]
     */
    public function getTextlocalClientConfig($notification): array;
}
