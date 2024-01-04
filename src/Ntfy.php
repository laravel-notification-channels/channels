<?php

namespace NotificationChannels\Ntfy;


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
            $request = new \GuzzleHttp\Psr7\Request('POST', $this->host . ':' . $this->port, [
                'auth' => [$this->username, $this->password],
                'headers' => [
                    'Content-Type' => 'application/json',
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
            $response = $this->client->sendRequest($request);
        } catch (\Exception $e) {
            throw new CouldNotSendNotification($e->getMessage());
        }
        return $response;
    }

}
