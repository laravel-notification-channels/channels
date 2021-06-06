<?php

namespace NotificationChannels\Onewaysms;

use NotificationChannels\Onewaysms\Exceptions\OnewaysmsException;
use Illuminate\Notifications\Notification;

class OnewaysmsChannel
{
    private $client;

    public function __construct(OnewaysmsClient $client)
    {
        $this->client = $client;
    }

    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toOnewaysms($notifiable);

        if ($to = $notifiable->routeNotificationFor('onewaysms')) {
            $message->to($to);
        }

        if (is_string($message)) {
            $message = new OnewaysmsMessage($message);
        }

        $response = $this->client->send($message);

        if ($response == '-100') {
            throw OnewaysmsException::invalidAPIAccount();
        } else if ($response == '-200') {
            throw OnewaysmsException::invalidSenderID();
        } else if ($response == '-300') {
            throw OnewaysmsException::invalidMobileNo();
        } else if ($response == '-400') {
            throw OnewaysmsException::invalidLanguageType();
        } else if ($response == '-500') {
            throw OnewaysmsException::invalidCharactersInMessage();
        } else if ($response == '-600') {
            throw OnewaysmsException::invalidInsufficientCreditBalance();
        }
    }
}
