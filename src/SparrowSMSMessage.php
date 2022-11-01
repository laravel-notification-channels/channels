<?php

namespace NotificationChannels\SparrowSMS;

class SparrowSMSMessage
{
    public string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }
}
