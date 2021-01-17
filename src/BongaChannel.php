<?php

namespace NotificationChannels\Bonga;

use NotificationChannels\Bonga\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;
use Osen\Bonga\Sms;
use Exception;

class BongaChannel
{
    protected $bonga;

    public function __construct(Sms $bonga)
    {
        $this->bonga = $bonga;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Bonga\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toBonga($notifiable);

        if (! $phoneNumber = $notifiable->routeNotificationFor('bonga')) {
            $phoneNumber = $notifiable->phone_number;
        }

        try {
            $this->bonga->send($phoneNumber, $message->getContent());
        } catch (Exception $e) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($e->getMessage());
        }
    }
}
