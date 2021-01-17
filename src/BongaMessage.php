<?php

namespace NotificationChannels\Bonga;

class BongaMessage
{
    /** @var string */
    protected $content;

    /**
     * Set content for this message.
     *
     * @param string $content
     * @return this
     */
    public function content(string $content): self
    {
        $this->content = trim($content);

        return $this;
    }

    /**
     * Get message content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
