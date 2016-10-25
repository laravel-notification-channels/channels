<?php

namespace NotificationChannels\DiscordWebhook;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Notifications\Notification;
use NotificationChannels\DiscordWebhook\Exceptions\CouldNotSendNotification;
use NotificationChannels\DiscordWebhook\Exceptions\InvalidMessage;

class DiscordWebhookChannel
{
    /**
     * The HTTP client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $http;

    /**
     * The Content-Type for the HTTP Request.
     *
     * @var string
     */
    protected $type;

    /**
     * Create a new Discord Webhook channel instance.
     *
     * @param \GuzzleHttp\Client $http
     */
    public function __construct(HttpClient $http)
    {
        $this->http = $http;
        $this->type = 'json';
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return mixed
     *
     * @throws \NotificationChannels\DiscordWebhook\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $url = $notifiable->routeNotificationFor('discord-webhook')) {
            return false;
        }
        $message = $notification->toDiscordWebhook($notifiable);

        $payload = $this->buildPayload($message);

        try {
            $response = $this->http->post($url, [$this->type => $payload]);

            return $this->getResponse($response);
        } catch (ClientException $e) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($e);
        } catch (\Exception $e) {
            throw CouldNotSendNotification::couldNotCommunicateWithDiscordWebhook($e->getMessage());
        }
    }

    /**
     * Get the response for the sent notification.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return array
     */
    protected function getResponse(ResponseInterface $response)
    {
        $code = $response->getStatusCode();

        if ($code == 200) {
            return $this->getMessage($response->getBody()->getContents());
        }

        return [
            'StatusCode' => $code,
            'ReasonPhrase' => $response->getReasonPhrase(),
        ];
    }

    /**
     * Get the message that has been sent to Discord.
     *
     * @param string $content
     *
     * @return string|array
     */
    protected function getMessage($content)
    {
        $obj = json_decode($content);
        if ($obj) {
            $content = $obj;
        }

        return $content;
    }

    /**
     * Build up a payload for the Discord Webhook.
     *
     * @param \NotificationChannels\DiscordWebhook\DiscordWebhookMessage $message
     *
     * @return array
     *
     * @throws \NotificationChannels\DiscordWebhook\Exceptions\InvalidMessage
     */
    protected function buildPayload(DiscordWebhookMessage $message)
    {
        if ($this->checkMessageEmpty($message)) {
            throw InvalidMessage::cannotSendAnEmptyMessage();
        }

        if (! is_null($message->file)) {
            return $this->buildMultipartPayload($message);
        }

        return $this->buildJSONPayload($message);
    }

    /**
     * Checks if the given Message is valid.
     *
     * @param \NotificationChannels\DiscordWebhook\DiscordWebhookMessage $message
     *
     * @return bool
     */
    protected function checkMessageEmpty($message)
    {
        if (is_null($message->content) && is_null($message->file) && is_null($message->embeds)) {
            return true;
        }

        return false;
    }

    /**
     * Build up a JSON payload for the Discord Webhook.
     *
     * @param \NotificationChannels\DiscordWebhook\DiscordWebhookMessage $message
     *
     * @return array
     */
    protected function buildJSONPayload(DiscordWebhookMessage $message)
    {
        $optionalFields = array_filter([
            'username' => data_get($message, 'username'),
            'avatar_url' => data_get($message, 'avatar_url'),
            'tts' => data_get($message, 'tts'),
            'timestamp' => data_get($message, 'timestamp'),
        ]);

        return array_merge([
            'content' => $message->content,
            'embeds' => $this->embeds($message),
        ], $optionalFields);
    }

    /**
     * Build up a Multipart payload for the Discord Webhook.
     *
     * @param \NotificationChannels\DiscordWebhook\DiscordWebhookMessage $message
     *
     * @return array
     *
     * @throws \NotificationChannels\DiscordWebhook\Exceptions\InvalidMessage
     */
    protected function buildMultipartPayload(DiscordWebhookMessage $message)
    {
        if (! is_null($message->embeds)) {
            throw InvalidMessage::embedsNotSupportedWithFileUploads();
        }

        $this->type = 'multipart';

        return collect($message)->forget('file')->reject(function ($value) {
            return is_null($value);
        })->map(function ($value, $key) {
            return ['name' => $key, 'contents' => $value];
        })->push($message->file)->values()->all();
    }

    /**
     * Format the message's embedded content.
     *
     * @param \NotificationChannels\DiscordWebhook\DiscordWebhookMessage $message
     *
     * @return array
     */
    protected function embeds(DiscordWebhookMessage $message)
    {
        return collect($message->embeds)->map(function (DiscordWebhookEmbed $embed) {
            return array_filter([
                'color' => $embed->color,
                'title' => $embed->title,
                'description' => $embed->description,
                'link' => $embed->url,
                'thumbnail' => $embed->thumbnail,
                'image' => $embed->image,
                'footer' => $embed->footer,
                'author' => $embed->author,
                'fields' => $embed->fields,
            ]);
        })->all();
    }
}
