<?php

namespace NotificationChannels\OneWaySms\Tests\TestSupport;

use Illuminate\Notifications\Notification;

class TestNotificationWithStringMessage extends Notification
{
    /** @return array<int, string> */
    public function via(mixed $notifiable): array
    {
        return ['one_way_sms'];
    }

    public function toOneWaySms(mixed $notifiable): string
    {
        return 'Plain string message';
    }
}
