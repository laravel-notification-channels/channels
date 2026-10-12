<?php

namespace NotificationChannels\IssueBadge\Test;

use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    public function __construct(protected mixed $message)
    {
    }

    public function toIssueBadge($notifiable): mixed
    {
        return $this->message;
    }
}
