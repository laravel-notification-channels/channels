<?php

namespace NotificationChannels\AllMySms\Test\Notifications;

use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    /**
     * Get AllMySms representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \NotificationChannels\AllMySms\AllMySmsMessage|string
     */
    public function toAllMySms($notifiable)
    {
        return 'Sms content';
    }
}
