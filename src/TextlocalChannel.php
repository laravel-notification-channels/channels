<?php

namespace NotificationChannels\Textlocal;

use Illuminate\Notifications\Notification;
use NotificationChannels\Textlocal\Exceptions\CouldNotSendNotification;

/**
 * Textlocal channel class which is used to interact with core
 * textlocal sdk and faciliate to send sms via
 * laravel notification system
 */
class TextlocalChannel
{
    private $client;
    private $sender;

    /**
     * creates a textlocal channel object by using the configs
     *
     * @param Textlocal $client
     */
    public function __construct(Textlocal $client)
    {
        $this->client = $client;
        $this->sender = config('textlocal.sender');
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
        if (! $numbers = $notifiable->routeNotificationFor('Textlocal')) {
            return;
        }

        if (empty($numbers)) {
            return;
        }

        if (!is_array($numbers)) {
            $numbers = [$numbers];
        }

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

        if (method_exists($notification, 'getSenderId')) {
            $this->sender = $notification->getSenderId();
        }

        try {
            $response = $this->client
                ->setUnicodeMode($unicode)
                ->sendSms($numbers, $message, $this->sender);

            return $response;
        } catch (\Exception $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception, $message);
        }
    }
}
