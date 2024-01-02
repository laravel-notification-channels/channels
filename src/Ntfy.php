<?php

namespace NotificationChannels\Ntfy;


use GuzzleHttp\Client;
use NotificationChannels\Ntfy\Exceptions\CouldNotSendNotification;

class Ntfy
{
    private $host;
    private $username;
    private $password;
    private $topic;
    private $client;
    private $port;

    public function __construct($host, $port, $username, $password, \Psr\Http\Client\ClientInterface $client)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->client = $client;
        $this->port = $port;
    }

    public function send(NtfyMessage $message)
    {
        try {

            $response = $this->client->request('POST', $this->host.':'.$this->port . '/' . $message->topic, [
                'auth' => [$this->username, $this->password],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    "message" => $message->content,
                    "title" => $message->title,
                    "priority" => $message->priority,

                ],
            ]);


        } catch (\Exception $e) {
            throw new CouldNotSendNotification($e->getMessage());
        }

        return $response;
    }


}
