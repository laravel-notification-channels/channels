<?php

namespace NotificationChannels\MoceanApi;

use Illuminate\Support\Arr;

class MoceanApiSmsMessage extends \Mocean\Message\Message
{
    /**
     * MoceanApiSmsMessage constructor.
     * @param string|null $from
     * @param string|null $to
     * @param string|null $text
     * @param array $extra
     */
    public function __construct($from = null, $to = null, $text = null, $extra = [])
    {
        parent::__construct($from, $to, $text, $extra);
    }
}
