<?php

namespace NotificationChannels\Smsapi;

use SMSApi\Api\Response\StatusResponse;
use Illuminate\Notifications\Notification;

class SmsapiChannel
{
    /**
     * @var SmsapiClient
     */
    protected $client;

    /**
     * @param SmsapiClient $client
     */
    public function __construct(SmsapiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  Notification $notification
     * @return StatusResponse
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSmsapi($notifiable);
        if (is_string($message)) {
            $message = new SmsapiSmsMessage($message);
        }

        if ($to = $notifiable->routeNotificationFor('smsapi')) {
            $message->to($to);
        } elseif ($group = $notifiable->routeNotificationFor('smsapi_group')) {
            $message->group($group);
        }

        return $this->client->send($message);
    }
}
