<?php

namespace NotificationChannels\CHANNEL_NAMESPACE;

use NotificationChannels\CHANNEL_NAMESPACE\Exceptions\CouldNotSendNotification;
use NotificationChannels\CHANNEL_NAMESPACE\Events\MessageWasSent;
use NotificationChannels\CHANNEL_NAMESPACE\Events\SendingMessage;
use Illuminate\Notifications\Notification;

class Channel
{
    public function __construct()
    {
        // Initialisation code here
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return void
     * @throws \NotificationChannels\CHANNEL_NAMESPACE\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $shouldSendMessage = event(new SendingMessage($notifiable, $notification), [], true) !== false;

        if (! $shouldSendMessage) {
            return;
        }

//        if (error) {
//            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
//        }

        event(new MessageWasSent($notifiable, $notification));
    }
}
