<?php

namespace NotificationChannels\Vodafone;

use GuzzleHttp\Client;
use NotificationChannels\Vodafone\Exceptions\CouldNotSendNotification;

class VodafoneClient
{
    /**
     * @var string Vodafone's API endpoint
     */
    protected $endpoint = 'https://www.smsertech.com/apisend';

    /** @var string Vodafone SMS username */
    protected $username;

    /** @var string Vodafone SMS password */
    protected $password;

    /**
     * VodafoneClient constructor.
     * @param $username
     * @param $password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;

        return $this;
    }

    /**
     * @param $from
     * @param $to
     * @param $message
     * @return mixed Vodafone API result
     */
    public function send($from, $to, $message)
    {
        $client = new Client();
        $res = $client->post($this->endpoint, [
            'form_params' => [
                'username' => $this->username,
                'password' => $this->password,
                'to' => $to,
                'message' => $message,
                'from' => $from,
                'format' => 'json',
                'flash' => 0,
            ],
        ]);

        if (! $res) {
            throw CouldNotSendNotification::serviceUnknownResponse();
        }

        $body = json_decode($res->getBody()->getContents())[0];

        if ($body->status === 'ERROR') {
            throw CouldNotSendNotification::serviceRespondedWithAnError($body);
        }

        return $body;
    }
}
