<?php

namespace NotificationChannels\ZonerSmsGateway;

class ZonerSmsGatewayMessage
{
    /**
     * The message content.
     *
     * @var string
     */
    public $content;

    /**
     * The phone number the message should be sent from.
     *
     * @var string
     */
    public $sender;

    /**
     * The phone number the message should be sent to.
     *
     * @var string
     */
    public $receiver;

    /**
     * Create a new message instance.
     *
     * @param  string  $content
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * Set the message content.
     *
     * @param  string  $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the phone number the message should be sent from.
     *
     * @param  string  $sender
     *
     * @return $this
     */
    public function sender($sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Set the phone number the message should be sent to.
     *
     * @param  string  $receiver
     *
     * @return $this
     */
    public function receiver($receiver)
    {
        $this->receiver = $receiver;

        return $this;
    }
}
