<?php

namespace NotificationChannels\RedsmsRu;

class RedsmsRuMessage
{
    /**
     * @param string $text
     */
    public $text = '';

    public function __construct($text = '')
    {
        $this->text = $text;
    }

    /**
     * Set the message text.
     *
     * @param string $text
     * @return $this
     */
    public function text($text)
    {
        $this->text = $text;

        return $this;
    }
}
