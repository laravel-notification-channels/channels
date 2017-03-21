<?php

namespace NotificationChannels\Alidayu;

use Illuminate\Notifications\Notification;

class AlidayuChannel
{
    /**
     * @var \NotificationChannels\Alidayu\Alidayu
     */
    protected $discord;

    /**
     * @param \NotificationChannels\Alidayu\Alidayu $alidayu
     */
    public function __construct(Alidayu $alidayu)
    {
        $this->alidayu = $alidayu;
    }

    /**
     * Send the given notification.
     *
     * @param mixed                                  $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Alidayu\Exceptions\CouldNotSendNotification
     *
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$to = $notifiable->routeNotificationFor('alidayu')) {
            return;
        }

        $message = $notification->toAlidayu($notifiable);

        $this->alidayu->send($message, $to);
    }
}
