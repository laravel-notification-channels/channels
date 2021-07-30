<?php

namespace NotificationChannels\SparrowSMS;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Notifications\Notification;
use NotificationChannels\SparrowSMS\Exceptions\CouldNotSendNotification;

class SparrowSMSChannel
{
    public string $endpoint;

    public string $token;

    public string $from;

    public function __construct(array $config = [])
    {
        $this->endpoint = $config['endpoint'];

        $this->token = $config['token'];

        $this->from = $config['from'];
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\SparrowSMS\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSparrowSMS($notifiable);

        if (is_string($message)) {
            $message = new SparrowSMSMessage($message);
        }

        $response = Http::post($this->endpoint, [
            'token' => $this->token,
            'from '=> $this->from,
            'to' => $notifiable->routeNotificationFor('sparrowsms'),
            'message' => $message->content
        ]);

        if ($response->status() === 403) {
            Log::error($response->body());

            throw CouldNotSendNotification::serviceRespondedWithAnError($response->json());
        }

        return $response->ok();
    }
}
