<?php

namespace NotificationChannels\DiscordWebhook\Test;

use Illuminate\Notifications\Notification;
use NotificationChannels\DiscordWebhook\DiscordWebhookMessage;

class TestNotification extends Notification
{
    public function toDiscordWebhook($notifiable)
    {
        return (new DiscordWebhookMessage())
            ->content('Test Message from Discord Webhook');
    }
}
