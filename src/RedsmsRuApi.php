<?php

namespace NotificationChannels\RedsmsRu;

use GuzzleHttp\Client as HttpClient;
use NotificationChannels\RedsmsRu\Exceptions\CouldNotSendNotification;

class RedsmsRuApi
{
    /** @const string */
    const BASE_URI = 'https://lk.redsms.ru';

    /** @const string */
    const SEND_URL = 'get/send.php';

    /** @var HttpClient */
    protected $httpClient;

    /** @var string */
    protected $login;

    /** @var string */
    protected $secret;

    /** @var string */
    protected $sender;

    public function __construct($login, $secret, $sender)
    {
        $this->login = $login;
        $this->secret = $secret;
        $this->sender = $sender;

        $this->httpClient = new HttpClient([
            'timeout' => 5,
            'connect_timeout' => 5,
            'base_uri' => self::BASE_URI,
        ]);
    }

    /**
     * @param string $phone recipient's phone
     * @param string $text text sms
     * @return array sms sent data
     * @throws CouldNotSendNotification
     */
    public function send($phone, $text)
    {
        $params = [
            'text'      => $text,
            'phone'     => $phone,
            'login'     => $this->login,
            'sender'    => $this->sender,
            'timestamp' => $this->getTimestamp(),
        ];

        $params['signature'] = $this->getSignature($params);

        $response = $this->httpClient->get(self::SEND_URL, ['query' => $params]);

        $response = json_decode($response->getBody()->getContents(), true);

        if (false !== ($error = $this->getError($response))) {
            throw CouldNotSendNotification::redsmsRespondedWithAnError($error);
        }

        return $response;
    }

    /**
     * Get error from response.
     *
     * @param array $response
     * @return false|mixed
     */
    protected function getError($response)
    {
        if (isset($response['error'])) {
            return $response['error'];
        }

        if (key($response) === 0) {
            $data = current(current($response));

            return empty($data['error']) ? false : $data['error'];
        }

        return false;
    }

    /**
     * @return int
     */
    protected function getTimestamp()
    {
        return (int) $this->httpClient->get('https://lk.redsms.ru/get/timestamp.php')->getBody()->getContents();
    }

    /**
     * @param array $params
     * @return string
     */
    protected function getSignature($params)
    {
        ksort($params);
        reset($params);

        return md5(implode($params).$this->secret);
    }
}
