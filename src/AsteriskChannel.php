<?php

namespace NotificationChannels\Asterisk;

use NotificationChannels\Asterisk\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;

class AsteriskChannel
{
    /**
     * Sender
     *
     * @var \NotificationChannels\Asterisk\Asterisk
     */
    protected $asterisk;

    /**
     * Channel constructor
     *
     * @param \NotificationChannels\Asterisk\Asterisk $asterisk
     */
    public function __construct(Asterisk $asterisk)
    {
        $this->asterisk = $asterisk;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Asterisk\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toAsterisk($notifiable);

        if (is_string($message)) {
            $message = AsteriskMessage::create($message);
        }

        if ($message->toNotGiven()) {
            if (!$to = $notifiable->routeNotificationFor('asterisk')) {
                throw CouldNotSendNotification::missingRecipient();
            }
            $message->to($to);
        }

        $params = $message->toArray();

        $this->asterisk->send($params);
    }
}
