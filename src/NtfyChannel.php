<?php

namespace NotificationChannels\Ntfy;

use NotificationChannels\Ntfy\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;

class NtfyChannel
{
    private $service;
    
    public function __construct(Ntfy $service)
    {
        $this->service = $service;
    }
    
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Ntfy\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        //$response = [a call to the api of your notification send]
        $this->service->send($notification->toNtfy($notifiable));

//        if ($response->error) { // replace this by the code need to check for errors
//            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
//        }
    }
}
