<?php

namespace NotificationChannels\AllMySms\Test\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\AllMySms\AllMySmsMessage;

class TestNotificationWithParameters extends Notification
{
    /**
     * Get AllMySms representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \NotificationChannels\AllMySms\AllMySmsMessage|string
     */
    public function toAllMySms($notifiable)
    {
        return (new AllMySmsMessage('Hello #param_1#'))
            ->parameters(['world']);
    }
}
