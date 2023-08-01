<?php

namespace NotificationChannels\KChat;

use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use NotificationChannels\KChat\Exceptions\CouldNotSendNotification;
use Psr\Http\Message\ResponseInterface;

class KChat
{
    /**
     * kChat API base URL.
     *
     * @var string
     */
    protected $baseUrl = null;

    /**
     * API HTTP client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * kChat API token.
     *
     * @var string
     */
    protected $token;

    /**
     * @param  \GuzzleHttp\Client  $http
     * @param  string  $baseUrl
     * @param  string  $token
     */
    public function __construct(HttpClient $http, $baseUrl, $token)
    {
        $this->httpClient = $http;
        $this->baseUrl = $baseUrl;
        $this->token = $token;
    }

    /**
     * Send a message to a kChat channel.
     *
     * @param  string  $verb
     * @param  string  $endpoint
     * @param  array  $data
     * @return array
     *
     * @throws \NotificationChannels\KChat\Exceptions\CouldNotSendNotification
     */
    public function send(array $data): ?ResponseInterface
    {
        if (is_null($this->baseUrl)) {
            throw CouldNotSendNotification::baseUrlMissing();
        }
        if (! isset($data['channel_id'])) {
            throw CouldNotSendNotification::channelMissing();
        }
        if (! isset($data['message'])) {
            throw CouldNotSendNotification::messageMissing();
        }

        try {
            $url = rtrim($this->baseUrl, '/').'/api/v4/posts';
            $response = $this->httpClient->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->token,
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode($data),
            ]);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::kChatRespondedWithAnError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithkChat($exception);
        }

        return $response;
    }
}
