<?php

namespace NotificationChannels\NetGsm;

class NetGsmMessage
{
    public $body = '';
    public $header = null;
    public $recipients = [];

    /**
     * @param  string  $body
     * @return self
     */
    public static function create($body = '')
    {
        return new static($body);
    }

    public function __construct($body = '')
    {
        if (! empty($body)) {
            $this->body = trim($body);
        }
    }

    /**
     * @param string $body
     * @return self
     */
    public function setBody($body)
    {
        $this->body = trim($body);

        return $this;
    }

    /**
     * @param $header
     * @return self
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * @param string|array $recipients
     * @return self
     */
    public function setRecipients($recipients)
    {
        if (! is_array($recipients)) {
            $recipients = [$recipients];
        }
        $this->recipients = $recipients;

        return $this;
    }
}
