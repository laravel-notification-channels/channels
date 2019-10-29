<?php

namespace NotificationChannels\Textlocal;

use Illuminate\Notifications\Notification;
use NotificationChannels\Textlocal\Exceptions\CouldNotSendNotification;

class TextlocalChannel
{
    private $client;
    private $sender;

    public function __construct(Textlocal $client)
    {
        // Initialisation code here
        $this->client = $client;
        $this->sender = config('services.sms.textlocal.sender');
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
        if (! $numbers = $notifiable->routeNotificationFor('sms')) {
            return;
        }
        //$numbers = (array) $notifiable->routeNotificationForSms();

        if (empty($numbers)) {
            return;
        }

        if (!is_array($numbers)) {
            $numbers = [$numbers];
        }
        
        //TODO check if numbers are correct

        // Get the message from the notification class
        $message = (string) $notification->toSms($notifiable);

        if (empty($message)) {
            return;
        }

        // Get unicode parameter from notification class
        $unicode = false;
        if (method_exists($notification, 'getUnicodeMode')) {
            $unicode = $notification->getUnicodeMode();
        }

        try {
            $response = $this->client->setUnicodeMode($unicode)
            ->sendSms($numbers, $message, $this->sender);

            return json_decode(json_encode($response), true);
        } catch (\Exception $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception, $message);
        }
    }
}
