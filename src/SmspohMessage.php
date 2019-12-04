<?php

namespace NotificationChannels\Smspoh;

class SmspohMessage
{
    /**
     * The message content.
     *
     * @var string
     */
    public $content;

    /**
     * The sander name the message should be sent from.
     *
     * @var string
     */
    public $sender;

    /**
     * Set the test message Send a test message to specific mobile number.
     *
     * @var bool
     */
    public $test;

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
     * Set the sender name the message should be sent from.
     *
     * @param string $sender
     * @return $this
     */
    public function sender($sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Set the test message Send a test message to specific mobile number.
     *
     * @param bool $test
     * @return $this
     */
    public function test($test)
    {
        $this->test = $test;

        return $this;
    }
}
