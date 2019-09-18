<?php

namespace NotificationChannels\FortySixElks;

use NotificationChannels\FortySixElks\Exceptions\CouldNotSendNotification;

class FortySixElksMMS extends FortySixElksMedia implements FortySixElksMediaInterface
{
    const ENDPOINT = 'https://api.46elks.com/a1/mms';
    public $type = 'MMS';

    public function __construct()
    {
        return parent::__construct();
    }

    public function image($url)
    {
        $this->payload['image'] = $url;

        return $this;
    }

    /**
     * @return $this
     */
    public function send()
    {
        try {
            $response = $this->client->request('POST', self::ENDPOINT, [
                'form_params' => [
                    'from'     => $this->from,
                    'message'  => $this->getContent(),
                    'to'       => $this->phone_number,
                    'image'    => isset($this->payload['image']) ? $this->payload['image'] : null,
                ],

            ]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $response = $e->getResponse();

            throw CouldNotSendNotification::serviceRespondedWithAnError($response->getBody()->getContents(), $response->getStatusCode());
        }

        return $this;
    }
}
