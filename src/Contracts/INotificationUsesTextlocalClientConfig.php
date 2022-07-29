<?php

namespace NotificationChannels\Textlocal\Contracts;

interface INotificationUsesTextlocalClientConfig
{
    /**
     * @param $notifiable
     * @return array [$username, $hash, $apiKey, $country]
     */
    public function getTextlocalClientConfig($notifiable): array;
}
