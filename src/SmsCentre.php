<?php

namespace NotificationChannels\SmsCentre;

use Exception;
use DomainException;
use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;
use NotificationChannels\SmsCentre\Exceptions\CouldNotSendNotification;

class SmsCentre
{
    const FORMAT_JSON = 3;

    /**
     * @var string
     */
    protected $apiUrl = 'http://smsc.ru/sys/send.php';

    /**
     * @var HttpClient
     */
    protected $httpClient;

    protected $login;
    protected $secret;
    protected $sender;

    public function __construct($login, $secret, $sender)
    {
        $this->login = $login;
        $this->secret = $secret;
        $this->sender = $sender;

        $this->httpClient = new HttpClient([
            'timeout' => 5,
            'connect_timeout' => 5,
        ]);
    }

    /**
     * @param  string  $recipient
     * @param  array   $params
     *
     * @return ResponseInterface
     *
     * @throws CouldNotSendNotification
     */
    public function send($recipient, $params)
    {
        $params = array_merge([
            'phones' => $recipient,
            'login'  => $this->login,
            'psw'    => $this->secret,
            'sender' => $this->sender,
            'fmt'    => self::FORMAT_JSON,
        ], $params);

        try {
            $response = $this->httpClient->post($this->apiUrl, ['form_params' => $params]);

            $response = json_decode((string) $response->getBody(), true);

            if (isset($response['error'])) {
                throw new DomainException($response['error'], $response['error_code']);
            }

            return $response;
        } catch (DomainException $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::serviceCommunicationError($exception);
        }
    }

}
