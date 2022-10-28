<?php

namespace NotificationChannels\MstatGr;

use Illuminate\Notifications\Notification;

class MstatGrChannel
{
    public function __construct(protected MstatGrClient $client)
    {
    }

    public function send(mixed $notifiable, Notification $notification)
    {
        $notificationData = $notification->toMstatGr($notifiable);
        $this->client->send($notifiable, $notificationData);
    }
}
