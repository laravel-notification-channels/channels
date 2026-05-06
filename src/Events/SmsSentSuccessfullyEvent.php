<?php

namespace NotificationChannels\TelnectSms\Events;

use Illuminate\Foundation\Events\Dispatchable;

class SmsSentSuccessfullyEvent
{
    use Dispatchable;

    public function __construct(public string $payload) {}
}
