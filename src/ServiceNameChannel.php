<?php

namespace NotificationChannels\ZonerSmsGateway;

use NotificationChannels\ZonerSmsGateway\Exceptions\CouldNotSendNotification;
use NotificationChannels\ZonerSmsGateway\Events\MessageWasSent;
use NotificationChannels\ZonerSmsGateway\Events\SendingMessage;
use Illuminate\Notifications\Notification;

class :service_nameChannel
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
     * @throws \NotificationChannels\ZonerSmsGateway\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        //$response = [a call to the api of your notification send]

//        if ($response->error) { // replace this by the code need to check for errors
//            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
//        }
    }
}
