<?php

namespace NotificationChannels\DiscordWebhook\Test;

use Illuminate\Notifications\Notification;
use NotificationChannels\DiscordWebhook\DiscordWebhookMessage;

class TestFileNotification extends Notification
{
    public function toDiscordWebhook($notifiable)
    {
        return (new DiscordWebhookMessage())->file('file content', 'file name');
    }
}
