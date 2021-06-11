<?php

namespace NotificationChannels\Expo\Test;

use Illuminate\Notifications\Notification;
use NotificationChannels\Expo\ExpoMessage;

class TestNotification extends Notification
{
    public function toExpo($notifiable)
    {
        return ExpoMessage::create()
            ->title('Title')
            ->body('Body')
            ->badge(1);
    }
}
