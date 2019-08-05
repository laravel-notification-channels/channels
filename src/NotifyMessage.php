<?php

namespace NotificationChannels\Notify;

class NotifyMessage
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

    /**
     * Message constructor.
     *
     */
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
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @param mixed $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    public function setParams(array $params)
    {
        $this->params = $params;

        return $this;
    }

    public function setNotificationType($notificationType)
    {
        $this->notificationType = $notificationType;

        return $this;
    }

    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    public function setTransport($transport)
    {
        $this->transport = $transport;

        return $this;
    }

    public function setCc($cc)
    {
        $this->cc = $cc;

        return $this;
    }

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
     */
    public function addRecipient($name = '', $email)
    {
        $this->to[] = array('name' => $name, 'recipient' => $email);

        return $this;
    }
}
