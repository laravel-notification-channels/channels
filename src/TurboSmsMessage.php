<?php

namespace NotificationChannels\TurboSms;

class TurboSmsMessage
{
    /** @var string */
    public $content;

    /** @var string */
    public $sender;

    /**
     * @param string $content
     *
     * @return static
     */
    public static function create(string $content = '')
    {
        return new static($content);
    }

    /**
     * @param string $content
     */
    public function __construct(string $content = '')
    {
        $this->content = $content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function content(string $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent() : string
    {
        return $this->content;
    }

    /**
     * @param string $sender
     *
     * @return $this
     */
    public function sender($sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return string
     */
    public function getSender() : string
    {
        return $this->sender ?: config('services.turbosms')['sender'];
    }
}
