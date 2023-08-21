<?php

namespace NotificationChannels\LaravelZenviaChannel;

abstract class ZenviaMessage
{
    /**
     * @var null|string
     */
    public $content;

    /**
     * Create a message object.
     * @param string $content
     * @return static
     */
    public static function create(string $content = '')
    {
        return new static($content);
    }

    /**
     * Create a new message instance.
     *
     * @param  string $content
     */
    public function __construct(string $content = '')
    {
        $this->content($content);
    }

    /**
     * Set the message content.
     *
     * @param  string $content
     * @return $this
     */
    public function content(string $content) : self
    {
        $this->content = $content;

        return $this;
    }
}
