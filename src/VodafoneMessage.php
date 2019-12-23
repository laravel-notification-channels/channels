<?php

namespace NotificationChannels\Vodafone;

class VodafoneMessage
{
    /**
     * @var string
     */
    public $content;

    /**
     * @var array
     */
    public $from;

    /**
     * Create a new message instance.
     *
     * @param  string  $content
     * @return void
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * Set the notification content.
     *
     * @param  string $value
     * @return $this
     */
    public function content($value)
    {
        $this->content = $value;

        return $this;
    }

    /**
     * Set the notification sender.
     *
     * @param  string $value
     * @return $this
     */
    public function from($value)
    {
        $this->from = $value;

        return $this;
    }

    /**
     * Set the message type.
     *
     * @return $this
     */
    public function unicode()
    {
        $this->type = 'unicode';

        return $this;
    }
}
