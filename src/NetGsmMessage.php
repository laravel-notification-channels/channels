<?php

namespace NotificationChannels\NetGsm;

class NetGsmMessage
{
    public $body = '';
    public $header = null;
    public $recipients = [];

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

    public function setBody($body)
    {
        $this->body = trim($body);

        return $this;
    }

    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    public function setRecipients($recipients)
    {
        if (! is_array($recipients)) {
            $recipients = [$recipients];
        }
        $this->recipients = $recipients;

        return $this;
    }
}
