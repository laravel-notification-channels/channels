<?php

namespace NotificationChannels\PusherApiNotifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\PusherApiNotifications\Exceptions\CouldNotSendNotification;

class PusherApiChannel
{
    /** @var \Pusher|\Pusher\Pusher $pusher */
    protected $pusher;

    public function __construct(\Pusher $pusher)
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
        /** @var PusherApiMessage|array $message */
        $message = $notification->toApiNotification($notifiable);

        $message = $message instanceof PusherApiMessage ? $message->toArray() : $message;

        $response = $this->pusher::trigger(
            $message['channels'],
            $message['event'],
            $message['data'],
            $message['socketId'] ?? null,
            $message['debug'] ?? false,
            $message['alreadyEncoded'] ?? false
        );

        if (!$response) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
        }
    }
}
