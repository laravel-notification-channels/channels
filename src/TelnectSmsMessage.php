<?php

namespace NotificationChannels\TelnectSms;

class TelnectSmsMessage
{
    public function __construct(
        protected string $body = '',
        protected string $sender = ''
    ) {
        //
    }

    /**
     * Creates a new instance of the message.
     */
    public static function create(): TelnectSmsMessage
    {
        return new self;
    }

    /**
     * Sets the message body.
     *
     * @return $this
     */
    public function body(string $content)
    {
        $this->body = trim($content);

        return $this;
    }

    /**
     * Get the message body.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Sets the message sender/originator identification.
     *
     * @return $this
     */
    public function originator(string $sender)
    {
        return $this->sender($sender);
    }

    /**
     * Sets the message sender identification.
     *
     * @return $this
     */
    public function sender(string $sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get the message sender identification.
     *
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }
}
