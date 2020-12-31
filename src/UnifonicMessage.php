<?php

namespace NotificationChannels\Unifonic;

class UnifonicMessage
{
    /**
     * The message content.
     *
     * @var string
     */
    public $body;

    /**
     * @param string $body
     */
    private function __construct(string $body = '')
    {
        $this->body($body);
    }

    /**
     * @param string $body
     *
     * @return $this
     */
    public function body(string $body)
    {
        $this->body = trim($body);

        return $this;
    }
    
    /**
     * get the content message.
     * 
     * @return string $body
     */
    public function getContent(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return static
     */
    public static function create($body = ''): self
    {
        return new static($body);
    }
}
