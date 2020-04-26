<?php

namespace NotificationChannels\SMS77;

use Illuminate\Notifications\Notification;
use NotificationChannels\SMS77\Exceptions\CouldNotSendNotification;

class SMS77Channel
{
    /**
     * @var SMS77
     */
    protected $sms77;

    /**
     * @param SMS77 $sms77
     */
    public function __construct(SMS77 $sms77)
    {
        $this->sms77 = $sms77;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\SMS77\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSms77($notifiable);

        // No SMS77Message object was returned
        if (is_string($message)) {
            $message = SMS77Message::create($message);
        }

        if (! $message->hasToNumber()) {
            if (! $to = $notifiable->phone_number) {
                $to = $notifiable->routeNotificationFor('sms');
            }

            if (! $to) {
                throw CouldNotSendNotification::phoneNumberNotProvided();
            }

            $message->to($to);
        }

        $params = $message->toArray();

        if ($message instanceof SMS77Message) {
            $response = $this->sms77->sendMessage($params);
        } else {
            return;
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
