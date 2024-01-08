<?php

namespace NotificationChannels\Ntfy;

use Illuminate\Notifications\Notification;
use NotificationChannels\Ntfy\Exceptions\CouldNotSendNotification;

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
     * @param  \Illuminate\Notifications\Notification  $notification
     *
     * @throws \NotificationChannels\Ntfy\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        //make sure that the $notification object has a toNtfy() method
        if (! method_exists($notification, 'toNtfy')) {
            throw CouldNotSendNotification::missingNtfyMethod();
        }
        $this->service->send($notification->toNtfy($notifiable));
    }
}
