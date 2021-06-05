<?php

namespace NotificationChannels\Onewaysms;

use DomainException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use NotificationChannels\Onewaysms\Exceptions\CouldNotSendNotification;

class OnewaysmsApi
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

    /**
     * @var string
     */
    protected $user;

    /**
     * @var string
     */
    protected $pwd;

    public function __construct($user = null, $pwd = null, HttpClient $httpClient = null)
    {
        $this->user = $user;
        $this->pwd = $pwd;
        $this->client = $httpClient;

        $this->endpoint = config('services.onewaysms.endpoint', 'https://gateway.onewaysms.com.my/api.aspx');
    }

    /**
     * Send text message.
     *
     * @link http://sms.onewaysms.com.my/api.pdf
     *
     * @param array $message
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws CouldNotSendNotification
     */
    public function send($message)
    {
        try {
            $response = $this->client->request('GET', $this->endpoint, [
                'query' => [
                    'apiusername' => Arr::get($message, 'user'),
                    'apipassword' => Arr::get($message, 'pwd'),
                    'mobileno' => Arr::get($message, 'to'), 
                    'senderid' => Arr::get($message, 'sender'),                    
                    'languagetype' => Arr::get($message, 'language', 1),
                    'message' => Arr::get($message, 'message'),
                ],
            ]);

            $response = json_decode((string) $response->getBody(), true);

            if (isset($response['error'])) {
                throw new DomainException($response['error'], $response['error_code']);
            }

            return $response;
        } catch (ClientException $e) {
            throw CouldNotSendNotification::OnewaysmsRespondedWithAnError($e);
        } catch (GuzzleException $e) {
            throw CouldNotSendNotification::couldNotCommunicateWithOnewaysms($e);
        }
    }

}