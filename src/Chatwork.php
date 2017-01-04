<?php

namespace NotificationChannels\Chatwork;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use NotificationChannels\Telegram\Exceptions\CouldNotSendNotification;

class Chatwork
{
    /** @var HttpClient HTTP Client */
    protected $http;

    /** @var null|string Chatwork API Token. */
    protected $token;

    /**
     * @param null            $token
     * @param HttpClient|null $httpClient
     */
    public function __construct($token = null, HttpClient $httpClient = null)
    {
        $this->token = $token;
        $this->http = $httpClient;
    }

    /**
     * Get HttpClient.
     *
     * @return HttpClient
     */
    protected function httpClient()
    {
        return $this->http ?: $this->http = new HttpClient();
    }

    /**
     * Send text message.
     *
     * <code>
     * $params = [
     *   'room_id' => '',
     *   'text' => '',
     * ];
     * </code>
     *
     * @param array $params
     *
     * @var int|string $params ['room_id']
     * @var string     $params ['text']
     *
     * @return bool
     */
    public function sendMessage($params)
    {
        if (empty($this->token)) {
            throw CouldNotSendNotification::serviceRespondedWithAnError('You must provide your chatwork api token to make any API requests.');
        }
        if (! array_key_exists('room_id', $params)) {
            throw CouldNotSendNotification::serviceRespondedWithAnError('Chatwork RoomId is empty');
        }
        if (! is_numeric($params['room_id'])) {
            throw CouldNotSendNotification::serviceRespondedWithAnError('Chatwork RoomId must be a number.');
        }

        $roomId = $params['room_id'];
        $message = $params['text'];

        $option = ['body' => $message];
        $ch = null;
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.chatwork.com/v1/rooms/'.$roomId.'/messages');
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-ChatWorkToken: '.$this->token]);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($option, '', '&'));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // or // curl_setopt($ch, CURLOPT_CAINFO, '/path/to/cacert.pem');
            $response = curl_exec($ch);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        } catch (\Exception $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        } finally {
            if ($ch != null) {
                curl_close($ch);
            }
        }

        return true;
    }
}
