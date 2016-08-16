<?php

namespace NotificationChannels\Gitter;

use Illuminate\Support\Arr;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Notifications\Notification;
use NotificationChannels\Gitter\Exceptions\CouldNotSendNotification;

class GitterChannel
{
    protected $baseUrl = 'https://api.gitter.im/v1/rooms';

    public function __construct(HttpClient $client)
    {
        $this->httpClient = $client;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Gitter\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        /** @var GitterMessage $message */
        $message = $notification->toGitter($notifiable);

        if (empty($message->room)) {
            $message->room($notifiable->routeNotificationFor('gitter'));
        };

        $this->sendMessage($message->toArray());
    }

    /**
     * @param  array  $message
     *
     * @throws CouldNotSendNotification
     */
    protected function sendMessage($message)
    {
        if (empty($room = Arr::pull($message, 'room'))) {
            throw CouldNotSendNotification::missingRoom();
        }

        if (empty($from = Arr::pull($message, 'from'))) {
            throw CouldNotSendNotification::missingFrom();
        }

        $options = [
            'json'    => $message,
            'headers' => [
                'Authorization' => "Bearer {$from}"
            ]
        ];

        try {
            $this->httpClient->post("{$this->baseUrl}/{$room}/chatMessages", $options);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::gitterRespondedWithAnError($exception);
        } catch (\Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithGitter($exception);
        }
    }
}
