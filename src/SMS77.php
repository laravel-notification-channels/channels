<?php

namespace NotificationChannels\SMS77;

use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHTtp\Exception\ClientException;
use NotificationChannels\SMS77\Exceptions\CouldNotSendNotification;

/**
 * Class SMS77.
 */
class SMS77
{
    /**
     * @var string SMS77 API URL.
     */
    protected string $apiUrl = 'https://gateway.sms77.io/api/';

    /**
     * @var HttpClient HTTP Client.
     */
    protected $http;

    /**
     * @var null|string SMS77 API Key.
     */
    protected $apiKey;

    /**
     * @param string $apiKey
     * @param HttpClient $http
     */
    public function __construct(string $apiKey = null, HttpClient $http = null)
    {
        $this->apiKey = $apiKey;
        $this->http = $http;
    }

    /**
     * Get API key.
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Set API key.
     *
     * @param string $apiKey
     */
    public function setApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Get HttpClient.
     *
     * @return HttpClient
     */
    protected function httpClient(): HttpClient
    {
        return $this->http ?? new HttpClient();
    }

    /**
     * Send text message.
     *
     * <code>
     * $params = [
     *      'to'                    => '',
     *      'text'                  => '',
     *      'from'                  => '',
     *      'debug'                 => '',
     *      'delay'                 => '',
     *      'no_reload'             => '',
     *      'unicode'               => '',
     *      'flash'                 => '',
     *      'udh'                   => '',
     *      'utf8'                  => '',
     *      'ttl'                   => '',
     *      'details'               => '',
     *      'return_msg_id'         => '',
     *      'label'                 => '',
     *      'json'                  => '',
     *      'performance_tracking'  => ''
     * ];
     * </code>
     *
     * @link https://www.sms77.io/en/docs/gateway/http-api/sms-disptach/
     *
     * @param array $params
     */
    public function sendMessage(array $params)
    {
        return $this->sendRequest('sms', $params);
    }

    public function sendRequest(string $endpoint, array $params)
    {
        if (empty($this->apiKey)) {
            throw CouldNotSendNotification::apiKeyNotProvided();
        }

        try {
            return $this->httpClient()->post($this->apiUrl.$endpoint, [
                'headers' => [
                    'Authorization' => 'basic '.$this->apiKey,
                ],
                'form_params' => $params,
            ]);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::serviceNotAvailable($exception);
        }
    }
}
