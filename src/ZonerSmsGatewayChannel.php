<?php

namespace NotificationChannels\ZonerSmsGateway;

use NotificationChannels\ZonerSmsGateway\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;

class ZonerSmsGatewayChannel
{
    /**
     * @var ZonerSmsGateway
     */
    protected $gateway;

    public function __construct(ZonerSmsGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @return tracking number
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('zoner-sms-gateway')) {
            return;
        }

        $message = $notification->toZonerSmsGateway($notifiable);

        if (is_string($message)) {
            $message = new ZonerSmsGatewayMessage($message);
        }

        return $this->gateway->sendMessage($to, $message->from, trim($message->content));
    }
}
