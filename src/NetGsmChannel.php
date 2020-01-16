<?php

namespace NotificationChannels\NetGsm;

use Illuminate\Notifications\Notification;

class NetGsmChannel
{
    /** @var \NotificationChannels\NetGsm\NetGsmClient */
    protected $client;

    /**
     * NetGsmChannel constructor.
     * @param  NetGsmClient  $client
     */
    public function __construct(NetGsmClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     *
     * @throws \NotificationChannels\NetGsm\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toNetGsm($notifiable);

        if (is_string($message)) {
            $message = NetGsmMessage::create($message);
        }

        if ($to = $notifiable->routeNotificationFor('netgsm')) {
            $message->setRecipients($to);
        }
        
        $this->client->send($message);
    }
}
