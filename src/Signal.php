<?php

namespace NotificationChannels\Signal;

class Signal
{
    public function __construct(SignalConfig $config)
    {
      /**
      *  @var string phone number for recipient;
      * ex: +12345556789, +442012345678
      **/
        $this->sender => env('SIGNAL_USERNAME', false);
    }
}
