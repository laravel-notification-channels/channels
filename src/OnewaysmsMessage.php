<?php

namespace NotificationChannels\Onewaysms;

class OnewaysmsMessage
{
    public $to;
    public $from;
    public $content;
    public $language;

    public function __construct($content = '')
    {
        $this->content = $content;
    }

    public function content($value)
    {
        $this->content = $value;

        return $this;
    }

    public function from($value)
    {
        $this->from = $value;

        return $this;
    }

    public function to($value)
    {
        $this->to = $value;

        return $this;
    }
}
