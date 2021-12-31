<?php

declare(strict_types=1);

namespace NotificationChannels\WXWork;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;
use NotificationChannels\WXWork\Exceptions\CouldNotSendNotification;

final class WXWorkChannel
{
    /**
     * The base uri.
     */
    protected string $base_uri;

    /**
     * The token.
     */
    protected string $token;

    /**
     * The url.
     */
    protected string $url;

    /**
     * The client.
     */
    protected Client $client;

    /**
     * Create a new WXWorkChannel instance.
     *
     * @return void
     */
    public function __construct(Client $client, string $token = null)
    {
        $this->client = $client;
        $this->token = $token ?? config('services.wxwork-bot-api.token');
        $this->base_uri = 'https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=';

        $this->url = $this->base_uri.$this->token;
    }

    /**
     * Send the given notification.
     *
     * @throws \NotificationChannels\WXWork\Exceptions\CouldNotSendNotification
     */
    public function send(
        mixed $notifiable,
        Notification $notification
    ): \GuzzleHttp\Psr7\Response {
        $token = $notifiable->routeNotificationFor('wxwork');
        if (! $token) {
            $token = $this->token;
        }

        $message = $notification->toWXWork($notifiable);

        $response = $this->client->post($this->url, ['body' => $message]);

        $this->checkWXWorkResponseError($response);

        return $response;
    }

    protected function checkWXWorkResponseError(
        \GuzzleHttp\Psr7\Response $response
    ): void {
        $error_msg = $response->getHeader('Error-Msg')[0];
        $error_code = intval($response->getHeader('Error-Code')[0]);

        if ($error_msg !== 'ok' || $error_code !== 0) {
            throw CouldNotSendNotification::wxworkRespondedWithAnError(
                $error_msg,
                $error_code
            );
        }
    }
}
