<?php

namespace NotificationChannels\PushoverNotifications;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use NotificationChannels\PushoverNotifications\Exceptions\CouldNotSendNotification;

class Pushover {
    /**
     * Location of the Pushover API.
     *
     * @var string
     */
    protected $pushoverApiUrl = 'https://api.pushover.net/1/messages.json';

    /**
     * The HTTP client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $http;


    /**
     * Pushover App Token.
     *
     * @var string
     */
    protected $token;

    /**
     * Pushover constructor.
     *
     * @param  HttpClient  $http
     * @param  $token
     */
    public function __construct(HttpClient $http, $token)
    {
        $this->http = $http;
        $this->token = $token;
    }

    /**
     * Send Pushover message.
     *
     * @link  https://pushover.net/api
     *
     * @param  array  $params
     * @return \Psr\Http\Message\ResponseInterface
     * @throws CouldNotSendNotification
     */
    public function send($params) {
        try {
            return $this->http->post($this->pushoverApiUrl, [
                'form_params' => $this->paramsWithToken($params)
            ]);
        } catch (RequestException $e) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($e->getResponse());
        } catch (\Exception $e) {
            throw CouldNotSendNotification::serviceCommunicationError();
        }
    }

    /**
     * Merge token into parameters array.
     *
     * @param  array  $params
     * @return array
     */
    protected function paramsWithToken($params)
    {
        return array_merge($params, [
            'token' => $this->token
        ]);
    }

}