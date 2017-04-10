<?php

namespace NotificationChannels\Smsapi;

use NotificationChannels\Smsapi\Exceptions\CouldNotSendNotification;
use NotificationChannels\Smsapi\Events\MessageWasSent;
use NotificationChannels\Smsapi\Events\SendingMessage;
use Illuminate\Notifications\Notification;

class SmsapiChannel
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
     * @throws \NotificationChannels\Smsapi\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        //$response = [a call to the api of your notification send]

//        if ($response->error) { // replace this by the code need to check for errors
//            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
//        }
    }
}
