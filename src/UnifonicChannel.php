<?php

namespace NotificationChannels\Unifonic;

use Illuminate\Notifications\Notification;

class UnifonicChannel
{
    /**
     * The Nexmo client instance.
     *
     * @var UnifonicClient
     */
    protected $client;

    /**
     * Create a new Unifonic channel instance.
     *
     * @param  UnifonicClient  $client
     * @return void
     */
    public function __construct(UnifonicClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Unifonic\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $recipient = $notifiable->routeNotificationFor('Unifonic')) {
            return;
        }

        $message = $notification->toUnifonic($notifiable);

        if (is_string($message)) {
            $message = UnifonicMessage::create($message);
        }

        $this->client->send($message, $recipient);
    }
}