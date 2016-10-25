<?php

namespace NotificationChannels\DiscordWebhook\Test;

use Illuminate\Notifications\Notification;
use NotificationChannels\DiscordWebhook\DiscordWebhookMessage;

class TestEmptyNotification extends Notification
{
    public function toDiscordWebhook($notifiable)
    {
        return new DiscordWebhookMessage();
    }
}
