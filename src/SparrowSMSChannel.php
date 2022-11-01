<?php

namespace NotificationChannels\SparrowSMS;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
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
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     *
     * @throws \NotificationChannels\SparrowSMS\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSparrowSMS($notifiable);

        if (is_string($message)) {
            $message = new SparrowSMSMessage($message);
        }

        $url = $this->endpoint.
            http_build_query([
                'token' => $this->token,
                'from' => $this->from,
                'to' => $notifiable->routeNotificationFor('sparrowsms'),
                'text' => $message->content,
            ]);

        $response = json_decode(file_get_contents($url));

        if ($response->status !== 200) {
            Log::error($response);

            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
        }

        return true;
    }
}
