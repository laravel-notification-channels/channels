<?php

namespace NotificationChannels\RealtimePushNotifications;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;
use NotificationChannels\RealtimePushNotifications\Exceptions\CouldNotSendNotification;

class RealtimeChannel
{
    const MOBILE_PUSH_ENDPOINT = 'https://ortc-mobilepush.realtime.co/mp/publishbatch';

    /** @var Client */
    protected $client;

    /**
     * @param Client $client
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\RealtimePushNotifications\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $realtimeConfig = config('services.realtimepush');

        $channel = collect($notifiable->routeNotificationFor('RealtimePush'));
        
        // if there are no valid channels then do not send the notification
        if (! $channel->count() > 0) {
            return;
        }

        $message = $notification->toRealtimePushMesssage($notifiable);

        $data = $message->toArray();
        $credencials = array('applicationKey' => $realtimeConfig['applicationKey'], 'privateKey' => $realtimeConfig['privateKey']);
        $data = array_merge($credencials, $data);
        $data['channels'] = $channel;

        $response = $this->client->post(self::MOBILE_PUSH_ENDPOINT, [
            'body' => json_encode($data),
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
        }

        return $response;
    }
}
