<?php

namespace NotificationChannels\Onewaysms;

use Illuminate\Notifications\Notification;
use NotificationChannels\Onewaysms\Exceptions\OnewaysmsException;

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
        } elseif ($response == '-200') {
            throw OnewaysmsException::invalidSenderID();
        } elseif ($response == '-300') {
            throw OnewaysmsException::invalidMobileNo();
        } elseif ($response == '-400') {
            throw OnewaysmsException::invalidLanguageType();
        } elseif ($response == '-500') {
            throw OnewaysmsException::invalidCharactersInMessage();
        } elseif ($response == '-600') {
            throw OnewaysmsException::invalidInsufficientCreditBalance();
        }
    }
}
