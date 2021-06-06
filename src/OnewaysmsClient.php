<?php

namespace NotificationChannels\Onewaysms;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use NotificationChannels\Onewaysms\Exceptions\OnewaysmsException;

class OnewaysmsClient
{
    protected $sendEndpoint = 'https://gateway.onewaysms.com.my/api.aspx';
    protected $username;
    protected $password;
    protected $sender_id;
    protected $languageType;
    protected $client;

    public function __construct()
    {
        $this->username = config('services.onewaysms.username');
        $this->password = config('services.onewaysms.password');
        $this->sendEndpoint = config('services.onewaysms.endpoint');
        $this->sender_id = config('services.onewaysms.sender');
        $this->languageType = config('services.onewaysms.language') ?? 1;
        $this->client = new Client();
    }

    public function send(OnewaysmsMessage $msg)
    {
        try {
            $response = $this->client->get($this->sendEndpoint, [
                'query' => [
                    'apiusername'   => $this->username,
                    'apipassword'   => $this->password,
                    'mobileno'      => $msg->to,
                    'message'       => $msg->content,
                    'senderid'      => $msg->from ?? $this->sender_id,
                    'languagetype'  => $msg->language ?? $this->languageType,
                ],
            ]);

            return (string) trim($response->getBody());
        } catch (ClientException $e) {
            throw OnewaysmsException::respondedWithAnError($e);
        } catch (GuzzleException $e) {
            throw OnewaysmsException::couldNotCommunicate($e);
        }
    }
}
