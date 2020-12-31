<?php

namespace NotificationChannels\Unifonic;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Arr;
use NotificationChannels\Unifonic\Exceptions\CouldNotSendNotification;

class UnifonicClient
{
    const GATEWAY_URL = 'https://api.unifonic.com/rest/Messages/Send';

    /**
     *  @var GuzzleClient
     * */
    protected $client;

    /**
     *  @var string
     * */
    protected $appsId;

    /**
     * @param GuzzleClient $client
     * @param string       $productToken
     */
    public function __construct(GuzzleClient $client, string $appsId)
    {
        $this->client = $client;
        $this->appsId = $appsId;
    }

    /**
     * @param UnifonicMessage $message
     * @param string       $recipient
     *
     * @throws CouldNotSendNotification
     */
    public function send(UnifonicMessage $message, string $recipient)
    {
        $response = $this->client->request('POST', static::GATEWAY_URL, [
            'form_params'    => $this->buildMessageParameters($message, $recipient),
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);

        $body = $response->getBody()->getContents();
        $array_body = json_decode($body, true);
        if (Arr::get($array_body, 'success') === 'false') {
            throw CouldNotSendNotification::serviceRespondedWithAnError($body);
        }
    }

    /**
     * @param UnifonicMessage $message
     * @param string       $recipient
     *
     * @return array $parameters
     */
    private function buildMessageParameters(UnifonicMessage $message, string $recipient): array
    {
        $mesage_body = $message->getContent();
        $parameters = array_merge(array_filter(['Body' => $mesage_body, 'Recipient' => $recipient]), [
            'AppSid' => $this->appsId,
        ]);

        return $parameters;
    }

}
