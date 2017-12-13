<?php

namespace NotificationChannels\ZonerSmsGateway;

use GuzzleHttp\Client as HttpClient;
use NotificationChannels\ZonerSmsGateway\Exceptions\CouldNotSendNotification;

class ZonerSmsGateway
{
    /** @var HttpClient HTTP Client */
    protected $http;

    /** @var string|null Zoner SMS-API username. */
    protected $username = null;

    /** @var string|null Zoner SMS-API password. */
    protected $password = null;

    /** @var string|null Default sender number or text. */
    protected $sender = null;

    /**
     * @param string|null $username
     * @param string|null $password
     * @param string|null $sender sender number or name
     * @param HttpClient|null $httpClient
     */
    public function __construct($username, $password, $sender = null, HttpClient $httpClient = null)
    {
        $this->username = $username;
        $this->password = $password;

        $this->sender = $sender;
        $this->http = $httpClient;
    }

    /**
     * Gets the HttpClient.
     *
     * @return HttpClient
     */
    protected function httpClient()
    {
        return $this->http ?: $this->http = new HttpClient();
    }

    /**
     * Sends a message via the gateway.
     *
     * @param string $receiver phone number where to send (for example "35840123456")
     * @param string $message message to send (UTF-8, but this function converts it to ISO-8859-15)
     * @param string|null $sender sender phone number (for example "35840123456")
     * or string (max 11 chars, a-ZA-Z0-9)
     *
     * @return tracking number
     *
     * @throws CouldNotSendNotification if sending failed.
     */
    public function sendMessage($receiver, $message, $sender = null)
    {
        if (empty($this->username)) {
            throw CouldNotSendNotification::usernameNotProvided();
        }

        if (empty($receiver)) {
            throw CouldNotSendNotification::numberToNotProvided();
        }

        if (empty($sender)) {
            if ($this->sender) {
                $sender = $this->sender;
            } else {
                throw CouldNotSendNotification::numberFromNotProvided();
            }
        }

        if (empty($message)) {
            throw CouldNotSendNotification::emptyMessage();
        }
        $endPointUrl = 'https://sms.zoner.fi/sms.php';

        $params = [
            'username' => $this->username,
            'password' => $this->password,
            'numberto' => $receiver,
            'numberfrom' => $sender,
            'message' => utf8_decode($message),
        ];

        $response = $this->httpClient()->post($endPointUrl, [
            'form_params' => $params,
        ]);
        if ($response->getStatusCode() === 200) {
            $body = $response->getBody();
            $statusAndCode = explode(' ', $body, 2);
            if ($statusAndCode[0] === 'OK') {
                return $statusAndCode[1];
            } elseif ($statusAndCode[0] === 'ERR') {
                throw CouldNotSendNotification::serviceRespondedWithAnError($statusAndCode[1]);
            }
        } else {
            throw CouldNotSendNotification::unexpectedHttpStatus($response);
        }
    }
}
