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
     * @param string $sender
     *
     * @return static
     */
    public static function create(string $content = '', string $sender = '')
    {
        return new static( $content, $sender );
    }

    /**
     * TurboSmsMessage constructor.
     *
     * @param string $content
     * @param string $sender
     */
    public function __construct(string $content = '', string $sender = '')
    {
        $this->content = $content;
        $this->sender = $sender;
    }

    /**
     * @param string $content
     *
     * @return TurboSmsMessage
     */
    public function content(string $content) : self
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
     * @return TurboSmsMessage
     */
    public function sender(string $sender) : self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return string
     */
    public function getSender() : string
    {
        return $this->sender ?: config( 'services.turbosms' )[ 'sender' ];
    }
}
