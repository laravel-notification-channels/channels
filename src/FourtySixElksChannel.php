<?php

namespace NotificationChannels\FourtySixElks;

use Illuminate\Notifications\Notification;

/**
 * Class FourtySixElksChannel.
 */
class FourtySixElksChannel
{
    /**
     * FourtySixElksChannel constructor.
     */
    public function __construct()
    {
        // Initialisation code here
    }

    /**
     * Send the given notification.
     *
     * @param mixed                                  $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\FourtySixElks\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $media = $notification->to46Elks($notification);
        $media->send();
    }
}
