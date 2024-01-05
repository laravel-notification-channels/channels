<?php

namespace NotificationChannels\Ntfy;


use GuzzleHttp\Psr7\Request;
use NotificationChannels\Ntfy\Exceptions\CouldNotSendNotification;

class Ntfy
{
    private $host;
    private $username;
    private $password;
    private $topic;
    private $client;
    private $port;

    public function __construct($host, $port, $username, $password)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->client = new \GuzzleHttp\Client();
        $this->port = $port;
    }

    public function send(NtfyMessage $message)
    {
        try {
            $response = $this->client->post($this->host . ':' . $this->port, [
                'auth' => [$this->username, $this->password],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => [
                    "topic" => $message->topic,
                    "message" => $message->content,
                    "title" => $message->title,
                    "priority" => $message->priority,
                    "tags" => [],
                    "attach" => "",
                    "filename" => "",
                    "click" => "",
                    "actions" => [],
                ],
            ]);
        } catch (\Exception $e) {
            throw new CouldNotSendNotification($e);
        }
        return $response;
    }

}
