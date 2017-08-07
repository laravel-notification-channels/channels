<?php

namespace NotificationChannels\Textlocal;

use NotificationChannels\Textlocal\Exceptions\CouldNotSendNotification;
use NotificationChannels\Textlocal\Events\MessageWasSent;
use NotificationChannels\Textlocal\Events\SendingMessage;
use Illuminate\Notifications\Notification;

class TextlocalChannel
{
    private $client;
    private $sender;

    public function __construct(Textlocal $client)
    {
        // Initialisation code here
        $this->client   = $client;
        $this->sender   = config('services.sms.textlocal.sender');
    }

    /**
     * Send the given notification.
     *
     * @param mixed                                  $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Textlocal\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        // Get the mobile number/s from the model
        $numbers = (array) $notifiable->routeNotificationForSms();

        if (empty($numbers)) {
            return;
        }

        if (!is_array($numbers) ) {
            $numbers = array($numbers);
        }

        // Get the message from the notification class
        $message = (string) $notification->toSms($notifiable);

        if (empty($message)) {
            return;
        }

        try {
            $response = $this->client->sendSms($numbers, $message, $this->sender);
            return json_decode(json_encode($response), true);

        } catch (\Exception $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        }
    }
}
