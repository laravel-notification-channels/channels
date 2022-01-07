<?php

namespace NotificationChannels\BulkGate\Test\Fixtures;

use Illuminate\Notifications\Notification;
use NotificationChannels\BulkGate\BulkGateChannel;

class InvalidMessageTestNotification extends Notification
{
    public function via($notifiable): array
    {
        return [BulkGateChannel::class];
    }

    public function toBulkGate($notifiable)
    {
        return 'Invalid message';
    }
}
