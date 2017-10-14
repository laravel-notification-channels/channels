<?php

namespace NotificationChannels\RedsmsRu;

use Illuminate\Notifications\Notification;
use NotificationChannels\RedsmsRu\Exceptions\CouldNotSendNotification;

class RedsmsRuChannel
{
    /** @var RedsmsRuApi */
    protected $api;

    public function __construct(RedsmsRuApi $api)
    {
        $this->api = $api;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return array
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $to = $notifiable->routeNotificationFor('redsmsru');

        if (empty($to)) {
            throw CouldNotSendNotification::missingRecipient();
        }

        $message = $notification->toRedsmsru($notifiable);

        if (is_string($message)) {
            $message = new RedsmsRuMessage($message);
        }

        return $this->api->send($to, $message->text);
    }
}
