<?php

namespace NotificationChannels\SMSGatewayMe;

use Illuminate\Support\Arr;

class SMSGatewayMeMessage
{
    public $text;

    public function text($text)
    {
        $this->text = $text;

        return $this;
    }
}
