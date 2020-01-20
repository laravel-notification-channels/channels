<?php

namespace NotificationChannels\TurboSMS;

class TurboSMSMessage
{
    /**
     * Message body.
     *
     * @var string
     */
    public $body;

    public function __construct(string $body)
    {
        $this->body = $body;
    }
}
