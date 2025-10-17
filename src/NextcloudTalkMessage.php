<?php

namespace NotificationChannels\NextcloudTalk;

class NextcloudTalkMessage
{
    /**
     * Channel token.
     *
     * @var string
     */
    protected $channel = null;

    /**
     * Message content.
     *
     * @var string
     */
    protected $content;

    public function __construct(string $content = '')
    {
        $this->content($content);
    }

    /**
     * Create a new instance of RocketChatMessage.
     *
     * @param  string  $content
     * @return static
     */
    public static function create(string $content = ''): self
    {
        return new static($content);
    }

    /**
     * Set the nextcloud channel token the message should be sent to.
     *
     * @param  string  $channel
     * @return $this
     */
    public function to(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Set the content of the message.
     *
     * @param  string  $content
     * @return $this
     */
    public function content(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get channel token.
     *
     * @return string|null
     */
    public function getChannel(): ?string
    {
        return $this->channel;
    }

    /**
     * Get message content.
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }
}
