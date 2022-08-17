<?php

namespace NotificationChannels\Textlocal\Contracts;

interface IUsesTextlocalClientConfig
{
    /**
     * This method would be responsible if the user wants to use custom config
     * for the current notification otherwise will work as is meaning using the
     * default config
     * @param $notification
     * @return bool
     */
    public function shouldUseCustomTextlocalConfig($notification): bool;
    /**
     * @param $notification
     * @return array [$username, $hash, $apiKey, $country]
     */
    public function getTextlocalClientConfig($notification): array;
}
