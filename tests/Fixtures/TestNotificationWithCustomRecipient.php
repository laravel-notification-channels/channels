<?php

namespace NotificationChannels\BulkGate\Test\Fixtures;

use Illuminate\Notifications\Notification;
use NotificationChannels\BulkGate\BulkGateChannel;
use NotificationChannels\BulkGate\BulkGateMessage;

class TestNotificationWithCustomRecipient extends Notification
{
    const RECIPIENT = '123456789';

    public function via($notifiable): array
    {
        return [BulkGateChannel::class];
    }

    public function toBulkGate($notifiable): BulkGateMessage
    {
        return BulkGateMessage::create('Hello world', self::RECIPIENT);
    }
}
