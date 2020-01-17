<?php

namespace NotificationChannels\TurboSMS;

class TurboSMSMessage
{
    public $to;
    public $body;

    public function __construct(string $body)
    {
        $this->body = $body;
    }
}
