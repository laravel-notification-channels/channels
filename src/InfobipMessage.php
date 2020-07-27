<?php

namespace NotificationChannels\Infobip;

class InfobipMessage
{
    public $content;

    public $from;

    /**
     * InfobipMessage constructor.
     *
     * @param string $content
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * @param $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the phone number this message is sent from.
     *
     * @param $from
     * @return $this
     */
    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get the phone number this message is sent from.
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }
}
