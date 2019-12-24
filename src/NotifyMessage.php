<?php

namespace NotificationChannels\Notify;

use JsonSerializable;

class NotifyMessage implements JsonSerializable
{
    private $clientId;
    private $secret;
    private $params;
    private $notificationType;
    private $language;
    private $transport;
    private $to;
    private $cc;
    private $bcc;

    public function __construct()
    {
    }

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param mixed $clientId
     * @return $this
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @param mixed $secret
     * @return $this
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @param $notificationType
     * @return $this
     */
    public function setNotificationType($notificationType)
    {
        $this->notificationType = $notificationType;

        return $this;
    }

    /**
     * @param $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @param $transport
     * @return $this
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * @param $cc
     * @return $this
     */
    public function setCc($cc)
    {
        $this->cc = $cc;

        return $this;
    }

    /**
     * @param $bcc
     * @return $this
     */
    public function setBcc($bcc)
    {
        $this->bcc = $bcc;

        return $this;
    }

    /**
     * @return string
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return mixed
     */
    public function getNotificationType()
    {
        return $this->notificationType;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @return mixed
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return mixed
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * @return mixed
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return mixed
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param string $name
     * @param string $email
     * @return $this
     */
    public function addRecipient($name, $email)
    {
        $this->to[] = ['name' => $name, 'recipient' => $email];

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'message' => [
                'notificationType' => $this->getNotificationType(),
                'language' => $this->getLanguage(),
                'params' => $this->getParams(),
                'customer' => [
                    'clientId' => $this->getClientId(),
                    'secretKey' => $this->getSecret(),
                ],
                'transport' => [
                    [
                        'type' => $this->getTransport(),
                        'recipients' => [
                            'to' => $this->getTo(),
                            'cc' => $this->getCc(),
                            'bcc' => $this->getBcc(),
                        ],
                    ],
                ],
            ],
        ];
    }
}
