<?php

namespace NotificationChannels\Spryng;

class SpryngMessage
{
    public string $body;

    public string $originator;

    public array $recipients;

    public function __construct(
        string $body = '',
        string $originator = '',
        array $recipients = []
     ) {
        $this->body = $body;
        $this->originator = $originator;
        $this->recipients = $recipients;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function setOriginator(string $originator): self
    {
        $this->originator = $originator;

        return $this;
    }

    public function setRecipients(array|string $recipients): self
    {
        $this->recipients = is_array($recipients) ? $recipients : [$recipients];

        return $this;
    }
}
