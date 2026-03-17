<?php

namespace NotificationChannels\KwtSms;

use Illuminate\Notifications\Notification;
use KwtSMS\KwtSMS;
use NotificationChannels\KwtSms\Exceptions\CouldNotSendNotification;

class KwtSmsChannel
{
    public function __construct(protected KwtSMS $client)
    {
    }

    public function send(mixed $notifiable, Notification $notification): ?array
    {
        $message = $notification->toKwtSms($notifiable);

        if (is_string($message)) {
            $message = KwtSmsMessage::create($message);
        }

        if (empty($message->getContent())) {
            return null;
        }

        $to = $notifiable->routeNotificationFor('KwtSms', $notification);

        if (empty($to)) {
            return null;
        }

        $sender = $message->getSender() ?? config('kwtsms.sender', 'KWT-SMS');

        $response = $this->client->send($to, $message->getContent(), $sender);

        if (isset($response['result']) && $response['result'] === 'ERROR') {
            throw CouldNotSendNotification::serviceRespondedWithError(
                $response['code'] ?? 'UNKNOWN',
                $response['description'] ?? 'Unknown error'
            );
        }

        return $response;
    }
}
