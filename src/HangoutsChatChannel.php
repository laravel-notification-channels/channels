<?php

namespace NotificationChannels;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use NotificationChannels\Exceptions\CouldNotSendNotification;
use Psr\Http\Message\ResponseInterface;

class HangoutsChatChannel
{
    /** @var Client */
    protected $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @return ResponseInterface|void
     *
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $url = $notifiable->routeNotificationFor('hangoutsChat')) {
            return;
        }

        $webhookData = $notification->{'toHangoutsChat'}($notifiable)->toArray();

        $response = $this->client->post($url, [
            'query' => Arr::get($webhookData, 'query'),
            'body' => json_encode(Arr::get($webhookData, 'data')),
            'verify' => Arr::get($webhookData, 'verify'),
            'headers' => Arr::get($webhookData, 'headers'),
        ]);

        if ($response->getStatusCode() >= 300 || $response->getStatusCode() < 200) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
        }

        return $response;
    }
}
