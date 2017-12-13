<?php

namespace NotificationChannels\ZonerSmsGateway;

use Illuminate\Notifications\Notification;
use NotificationChannels\ZonerSmsGateway\Exceptions\CouldNotSendNotification;

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
        $message = $notification->toZonerSmsGateway($notifiable);

        if (is_string($message)) {
            $message = new ZonerSmsGatewayMessage($message);
        }

        $receiver = $message->receiver;
        if (empty($receiver)) {
            if (! $receiver = $notifiable->routeNotificationFor('zoner-sms-gateway')) {
                throw CouldNotSendNotification::receiverNotProvided();
            }
        }

        return $this->gateway->sendMessage($receiver, trim($message->content), $message->sender);
    }
}
