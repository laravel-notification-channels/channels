<?php

namespace NotificationChannels\MstatGr;

use NotificationChannels\MstatGr\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;
use NotificationChannels\MstatGr\Exceptions\InvalidParameter;

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
