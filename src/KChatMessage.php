<?php

namespace NotificationChannels\KChat;

use NotificationChannels\KChat\Exceptions\CouldNotSendNotification;

class KChatMessage
{
    /** @var array Params payload. */
    protected $payload = [];

    /**
     * @param  string  $content
     * @return self
     */
    public static function create(string $content = ''): self
    {
        return new self($content);
    }

    public function __construct(string $content = '')
    {
        $this->content($content);
    }

    /**
     * @param  string  $channel_id
     * @return self
     */
    public function to(string $channel_id): self
    {
        if (! $channel_id) {
            throw CouldNotSendNotification::channelMissing();
        }
        $this->payload['channel_id'] = $channel_id;

        return $this;
    }

    /**
     * @param  string  $content
     * @return self
     */
    public function content(string $content): self
    {
        $this->payload['message'] = $content;

        return $this;
    }

    /**
     * @param  string  $root_id
     * @return self
     */
    public function commentTo(string $root_id): self
    {
        if ($root_id) {
            $this->payload['root_id'] = $root_id;
        }

        return $this;
    }

    /**
     * Determine if channel id is not given.
     *
     * @return bool
     */
    public function toNotGiven(): bool
    {
        return ! isset($this->payload['channel_id']);
    }

    /**
     * Get payload value for given key.
     *
     * @param  string  $key
     * @return mixed|null
     */
    public function getPayloadValue(string $key)
    {
        return $this->payload[$key] ?? null;
    }

    /**
     * Returns params payload.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->payload;
    }
}
