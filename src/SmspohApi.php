<?php

namespace NotificationChannels\Smspoh;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use NotificationChannels\Smspoh\Exceptions\CouldNotSendNotification;

class SmspohApi
{
    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var string
     */
    protected $sender;

    public function __construct($token = null, HttpClient $httpClient = null)
    {
        $this->token = $token;
        $this->client = $httpClient;

        $this->endpoint = config('services.smspoh.endpoint', 'https://smspoh.com/api/v2/send');
    }

    /**
     * Send text message.
     *
     * <code>
     * $message = [
     *   'sender'   => '',
     *   'to'       => '',
     *   'message'  => '',
     *   'test'     => '',
     * ];
     * </code>
     *
     * @link https://smspoh.com/rest-api-documentation/send?version=2
     *
     * @param array $message
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws CouldNotSendNotification
     */
    public function send($message)
    {
        try {
            $response = $this->client->request('POST', $this->endpoint, [
                'headers' => [
                    'Authorization' => "Bearer {$this->token}",
                ],
                'json' => [
                    'sender' => Arr::get($message, 'sender'),
                    'to' => Arr::get($message, 'to'),
                    'message' => Arr::get($message, 'message'),
                    'test' => Arr::get($message, 'test', 0),
                ],
            ]);

            $response = json_decode((string) $response->getBody(), true);

            if (isset($response['error'])) {
                throw new \DomainException($response['error'], $response['error_code']);
            }

            return $response;
        } catch (ClientException $e) {
            throw CouldNotSendNotification::smspohRespondedWithAnError($e);
        } catch (GuzzleException $e) {
            throw CouldNotSendNotification::couldNotCommunicateWithSmspoh($e);
        }
    }
}
