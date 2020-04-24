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
    protected string $api_url = 'https://gateway.sms77.io/api/';

    /**
     * @var HttpClient HTTP Client.
     */
    protected $http;

    /**
     * @var null|string SMS77 API Key.
     */
    protected $api_key;

    /**
     * @param string $api_key
     * @param HttpClient $http
     */
    public function __construct(string $api_key = null, HttpClient $http = null)
    {
        $this->api_key = $api_key;
        $this->http = $http;
    }

    /**
     * Get API key.
     * 
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->api_key;
    }

    /**
     * Set API key.
     * 
     * @param string $api_key
     */
    public function setApiKey(string $api_key)
    {
        $this->api_key = $api_key;
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
        if (empty($this->api_key)) {
            throw CouldNotSendNotification::apiKeyNotProvided();
        }

        $request_url = $this->api_url . $endpoint;

        try {
            return $this->httpClient()->post($request_url, [
                'headers' => [
                    'Authorization' => 'basic ' . $this->api_key
                ],
                'form_params' => $params
            ]);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::serviceNotAvailable($exception);
        }
    }
}
