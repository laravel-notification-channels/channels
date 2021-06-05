<?php

namespace NotificationChannels\Onewaysms;

class OnewaysmsMessage
{
    /**
     * The message content.
     *
     * @var string
     */
    public $content;

    /**
     * The sender id.
     *
     * @var string
     */
    public $sender;

    /**
     * Create a new message instance.
     *
     * @param string $content
     * @return void
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * Set the message content.
     *
     * @param string $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the sender id.
     *
     * @param string $sender
     * @return $this
     */
    public function sender($sender)
    {
        $this->sender = $sender;

        return $this;
    }
    
}