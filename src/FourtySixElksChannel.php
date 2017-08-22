<?php

namespace NotificationChannels\FourtySixElks;

use NotificationChannels\FourtySixElks\Exceptions\CouldNotSendNotification;
use NotificationChannels\FourtySixElks\Events\MessageWasSent;
use NotificationChannels\FourtySixElks\Events\SendingMessage;
use Illuminate\Notifications\Notification;

/**
 * Class FourtySixElksChannel
 * @package NotificationChannels\FourtySixElks
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
     * @param mixed $notifiable
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
