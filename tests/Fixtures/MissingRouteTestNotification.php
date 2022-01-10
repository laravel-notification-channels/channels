<?php

namespace NotificationChannels\BulkGate\Test\Fixtures;

use Illuminate\Notifications\Notification;
use NotificationChannels\BulkGate\BulkGateChannel;

class MissingRouteTestNotification extends Notification
{
    public function via($notifiable): array
    {
        return [BulkGateChannel::class];
    }

    public function toMail($notifiable)
    {
        return 'Invalid message';
    }
}
