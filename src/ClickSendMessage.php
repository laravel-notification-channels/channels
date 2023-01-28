<?php

namespace NotificationChannels\ClickSend;

use ClickSend\Api\SMSApi;

class ClickSendMessage
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
    public $from;

    /**
     * The message type.
     *
     * @var string
     */
    public $type = 'text';

    /**
     * The custom ClickSend client instance.
     *
     * @var SMSApi|null
     */
    public $client;

    /**
     * The client reference.
     *
     * @var string
     */
    public $reference = '';

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
     * Set the message content.
     *
     * @param  string  $content
     * @return ClickSendMessage
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the phone number the message should be sent from.
     *
     * @param  string  $from
     * @return $this
     */
    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Set the client reference (up to 40 characters).
     *
     * @param  string  $string
     * @return $this
     */
    public function reference($string)
    {
        $this->reference = $string;

        return $this;
    }

    /**
     * Set the ClickSend SMS client instance.
     *
     * @param  SMSApi  $client
     * @return $this
     */
    public function usingClient($client)
    {
        $this->client = $client;

        return $this;
    }
}
