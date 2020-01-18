<?php

namespace NotificationChannels\TurboSMS;

class TurboSMSMessage
{
    /**
     * Message receiver
     *
     * @var string
     */
    public $to;

    /**
     * Message body
     *
     * @var string
     */
    public $body;

    public function __construct(string $body)
    {
        $this->body = $body;
    }
}
