<?php

namespace NotificationChannels\BulkGate\Test\Fixtures;

use Illuminate\Notifications\Notification;
use NotificationChannels\BulkGate\BulkGateChannel;
use NotificationChannels\BulkGate\BulkGateMessage;

class TestNotificationStaticCreate extends Notification
{
    public function via($notifiable): array
    {
        return [BulkGateChannel::class];
    }

    public function toBulkGate($notifiable): BulkGateMessage
    {
        return (new BulkGateMessage())->text('Test message');
    }
}
