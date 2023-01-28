<?php

namespace NotificationChannels\ClickSend;

use ClickSend\Api\SMSApi as ClickSendClient;
use ClickSend\ApiException;
use ClickSend\Model\SmsMessage;
use ClickSend\Model\SmsMessageCollection;
use Illuminate\Notifications\Notification;
use NotificationChannels\ClickSend\Exceptions\CouldNotSendNotification;

class ClickSendChannel
{
    /**
     * The ClickSend client instance.
     *
     * @var ClickSendClient
     */
    protected $client;

    /**
     * The phone number notifications should be sent from.
     *
     * @var string
     */
    protected $from;

    /**
     * Create a new ClickSend channel instance.
     *
     * @param  ClickSendClient  $client
     * @param  string  $from
     * @return void
     */
    public function __construct(ClickSendClient $client, $from)
    {
        $this->from = $from;
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     * @return mixed
     */
    public function send($notifiable, Notification $notification): void
    {
        if (! $to = $notifiable->routeNotificationFor('ClickSend', $notification)) {
            return;
        }

        $message = $notification->toClickSend($notifiable);

        if (is_string($message)) {
            $message = new ClickSendMessage($message);
        }

        $sms_messages = (new SmsMessageCollection())->setMessages([
            new SmsMessage([
                'to' => $to,
                'body' => trim($message->content),
                'from' => $message->from ?: $this->from,
                'source' => 'laravel',
                'custom_string' => $message->reference,
            ]),
        ]);

        try {
            ($message->client ?? $this->client)->smsSendPost($sms_messages);
        } catch (ApiException $e) {
            throw CouldNotSendNotification::ClickSendApiException($e);
        }
    }
}
