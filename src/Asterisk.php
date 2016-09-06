<?php

namespace NotificationChannels\Asterisk;

use Illuminate\Support\Facades\Artisan;

class Asterisk
{
    /**
     * Send message.
     *
     * @param array $params
     *
     * @var string $params ['device']
     * @var string $params ['number']
     * @var string $params ['message']
     *
     * @return mixed
     */
    public function send(array $params = [])
    {
        return Artisan::call('ami:dongle:sms', $params);
    }
}
