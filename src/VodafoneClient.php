<?php

namespace NotificationChannels\Vodafone;

use GuzzleHttp\Client;
use NotificationChannels\Vodafone\Exceptions\CouldNotSendNotification;

class VodafoneClient 
{
    /**
     * @var String Vodafone's API endpoint
     */
    protected $endpoint = 'https://www.smsertech.com/apisend';

    /** @var String Vodafone SMS username */
    protected $username;
    
    /** @var String Vodafone SMS password  */
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
     * @return mixed Guzzle result
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
            ]
        ]);

        if(!$res) {
            throw CouldNotSendNotification::serviceUnknownResponse();
        }

        $body = json_decode($res->getBody()->getContents())[0];

        if ($body->status === 'ERROR') {
            throw CouldNotSendNotification::serviceRespondedWithAnError($body);
        }

        return $body;
    }
}