<?php

namespace NotificationChannels\Gitter;

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

        $to = $notifiable->routeNotificationFor('gitter');
        $to = $to ?: $message->room;

        if (empty($to)) {
            throw CouldNotSendNotification::missingTo();
        }

        $this->sendMessage($to, $message->from, $message->toArray());
    }

    protected function sendMessage($to, $from, $message)
    {
        $options = [
            'json'    => $message,
            'headers' => [
                'Authorization' => "Bearer {$from}"
            ]
        ];

        try {
            $this->httpClient->post("{$this->baseUrl}/{$to}/chatMessages", $options);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::gitterRespondedWithAnError($exception);
        } catch (\Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithGitter($exception);
        }
    }
}
