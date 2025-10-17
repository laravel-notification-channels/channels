<?php

namespace NotificationChannels\NextcloudTalk;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use NotificationChannels\NextcloudTalk\Exceptions\CouldNotSendNotification;

class NextcloudTalk
{
    /**
     * API http client
     *
     * @var \GuzzleHttp\Client
     */
    protected $http;


    /**
     * Default channel token to send notification if no channel specified in notification
     *
     * @var string
     */
    protected $defaultChannelToken;

    /**
     * Channel name to create for bot to user conversations
     *
     * @var string
     */
    protected $oneToOneChannelName;

    /**
     * Talk api endpoint
     *
     * @var string
     */
    protected $baseEndpoint = '/ocs/v2.php/apps/spreed/api';

    public function __construct(Client $http, string $defaultChannelToken, string $oneToOneChannelName)
    {
        $this->http = $http;
        $this->defaultChannelToken = $defaultChannelToken;
        $this->oneToOneChannelName = $oneToOneChannelName;
    }


    /**
     * Send message to a specific channel
     *
     * @param string $channelToken
     * @param string $message
     * @return Response
     */
    public function sendMessage(string $channelToken, string $message): Response
    {
        try {
            $url = sprintf('%s/v1/chat/%s', $this->baseEndpoint, $channelToken);
            return $this->http->post($url, [
                'json' => ['message' => $message]
            ]);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::nextcloudApiError($exception);
        }

    }

    /**
     * Send message to a user by creating or reusing
     * existing channel between the user and the bot
     *
     * @param string $participant
     * @param string $message
     * @return Response
     */
    public function sendMessageToUser(string $participant, string $message): Response
    {
        try {
            $url = sprintf('%s/v4/room', $this->baseEndpoint);
            $response = $this->http->post($url, [
                'json' => [
                    "roomType" => 1,
                    "invite" => $participant,
                    "roomName" => $this->oneToOneChannelName
                ]
            ]);
            $requestReponse = json_decode($response->getBody(), true);
            return $this->sendMessage($requestReponse['ocs']['data']['token'], $message);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::nextcloudApiError($exception);
        }
    }


    /**
     * Get the default channel token
     *
     * @return string
     */
    public function getDefaultChannel(): string
    {
        return $this->defaultChannelToken;
    }
}
