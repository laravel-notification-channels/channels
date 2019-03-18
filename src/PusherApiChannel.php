<?php

namespace NotificationChannels\PusherApiNotifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\PusherApiNotifications\Exceptions\CouldNotSendNotification;
use Pusher\Laravel\PusherManager;

class PusherApiChannel
{
    /** @var PusherManager|\Pusher\Pusher $pusher */
    protected $pusher;

    public function __construct(PusherManager $pusher)
    {
        $this->pusher = $pusher;
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
        $message = $notification->toApiNotification($notifiable);

        $response = $this->pusher->trigger(
            $message['channel'],
            $message['event'],
            $message['message']
        );

        if (!$response) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
        }
    }
}
