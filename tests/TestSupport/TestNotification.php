<?php

namespace NotificationChannels\OneWaySms\Tests\TestSupport;

use Illuminate\Notifications\Notification;
use NotificationChannels\OneWaySms\OneWaySmsMessage;

class TestNotification extends Notification
{
    public function __construct(protected ?OneWaySmsMessage $message = null)
    {
    }

    /** @return array<int, string> */
    public function via(mixed $notifiable): array
    {
        return ['one_way_sms'];
    }

    public function toOneWaySms(mixed $notifiable): OneWaySmsMessage
    {
        return $this->message ?: OneWaySmsMessage::create('Test message');
    }
}
