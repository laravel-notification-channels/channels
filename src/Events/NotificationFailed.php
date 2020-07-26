<?php

namespace NotificationChannels\Infobip\Events;

use Illuminate\Notifications\Notification;

class NotificationFailed
{
    private $notifiable;

    /**
     * @var Notification
     */
    private $notification;

    /**
     * @var \Exception
     */
    private $exception;

    /**
     * NotificationFailed constructor.
     *
     * @param $notifiable
     * @param Notification $notification
     * @param \Exception $exception
     */
    public function __construct($notifiable, Notification $notification, \Exception $exception)
    {
        $this->notifiable = $notifiable;
        $this->notification = $notification;
        $this->exception = $exception;
    }
}
