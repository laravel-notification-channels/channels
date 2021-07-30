<?php

namespace NotificationChannels\SparrowSMS;

use Illuminate\Support\Arr;

class SparrowSMSMessage
{
    public string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }
}
