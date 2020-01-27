<?php

namespace NotificationChannels\TransmitMessage;

use Illuminate\Notifications\Notification;
use NotificationChannels\TransmitMessage\Exceptions\CouldNotSendNotification;
use TransmitMessageLib\Models;
use TransmitMessageLib\TransmitMessageClient;

class TransmitMessageChannel
{

    protected $client;

    public function __construct(TransmitMessageClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\TransmitMessage\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toTransmitMessage($notifiable);

        if ($sMSController = ($this->client)->getSMS()) {
            $body = new Models\SMS;
            $body->sender = $message->sender;
            $body->recipient = $message->recipient;
            $body->message = $message->message;
            echo $message->recipient;
            try {
                $result = $sMSController->sendSMS($body);
 
                return $result;
            } catch (TransmitMessageLib\APIException $e) {
                throw CouldNotSendNotification::serviceRespondedWithAnError($e);
            }
        }
    }
}
