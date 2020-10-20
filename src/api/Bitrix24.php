<?php

namespace NotificationChannels\Bitrix24\Api;

use NotificationChannels\Bitrix24\Exceptions\CouldNotSendNotification;

class Bitrix24
{
    /**
     * @var string OAuth-токен вебхука
     */
    private $token;

    /**
     * @var int
     */
    private $fromUserId;

    /**
     * @var string Company domain in Bitrix24
     */
    private $domain;

    /**
     * Bitrix24 constructor.
     *
     * @throws CouldNotSendNotification
     */
    public function __construct()
    {
        $this->token = config('bitrix24_notice.token');

        if (empty($this->token)) {
            throw CouldNotSendNotification::notToken();
        }

        $this->fromUserId = (int) config('bitrix24_notice.fromUserId');

        if (empty($this->fromUserId)) {
            throw CouldNotSendNotification::notUserId();
        }

        $this->domain = config('bitrix24_notice.domain');

        if (empty($this->domain)) {
            throw CouldNotSendNotification::notDomain();
        }
    }

    /**
     * Preparing and executing a request to the Bitrix24 API.
     *
     * @param array $params
     * @return void
     * @throws CouldNotSendNotification
     */
    public function send(array $params)
    {
        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->geuUrlForSend());
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->getHeaders());
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        } catch (\Exception $e) {
            throw CouldNotSendNotification::notConnect($e->getMessage());
        }
    }

    /**
     * Service address for sending JSON requests (case-sensitive).
     *
     * @return string
     */
    private function geuUrlForSend(): string
    {
        return 'https://'.$this->domain.'.bitrix24.ru/rest/'.$this->fromUserId.'/'.$this->token.'/im.message.add.json';
    }

    /**
     * Headers for sending JSON requests.
     *
     * @return array
     */
    private function getHeaders(): array
    {
        return [
            'Accept-Language: ru',
            'Content-Type: application/json; charset=utf-8',
        ];
    }
}
