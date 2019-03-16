<?php

namespace NotificationChannels\PusherApiNotifications;

use NotificationChannels\PusherApiNotifications\Exceptions\CouldNotSendNotification;
use NotificationChannels\PusherApiNotifications\Events\MessageWasSent;
use NotificationChannels\PusherApiNotifications\Events\SendingMessage;
use Illuminate\Notifications\Notification;

class PusherApiChannel
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
     * @throws \NotificationChannels\PusherApiNotifications\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        //$response = [a call to the api of your notification send]

        //        if ($response->error) { // replace this by the code need to check for errors
        //            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
        //        }
    }
}
