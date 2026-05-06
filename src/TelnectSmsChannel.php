<?php

namespace NotificationChannels\TelnectSms;

use Illuminate\Notifications\Notification;

class TelnectSmsChannel
{
    public function __construct(
        protected TelnectSmsClient $client,
    ) {}

    public function send($notifiable, Notification $notification): void
    {
        if (! $recipient = $notifiable->routeNotificationFor('TelnectSms')) {
            return;
        }

        $message = $notification->toTelnectSms($notifiable);

        if (is_string($message)) {
            $message = TelnectSmsMessage::create()
                ->body($message);
        }

        $this->client->send($message, $recipient);
    }
}
