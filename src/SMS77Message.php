<?php

namespace NotificationChannels\SMS77;

class SMS77Message
{
    protected $payload = [];

    /**
     * @param string $message
     */
    public function __construct(string $message = '')
    {
        $this->payload['text'] = $message;
        $this->payload['json'] = 1;
    }

    /**
     * Get the payload value for a given key.
     *
     * @param string $key
     *
     * @return mixed|null
     */
    public function getPayloadValue(string $key)
    {
        return $this->payload[$key] ?? null;
    }

    /**
     * Return new SMS77Message object.
     *
     * @param string $message
     */
    public static function create(string $message = ''): self
    {
        return new self($message);
    }

    /**
     * Returns if recipient number is given or not.
     *
     * @return bool
     */
    public function hasToNumber(): bool
    {
        return isset($this->payload['to']);
    }

    /**
     * Return payload.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->payload;
    }

    /**
     * Set message content.
     *
     * @param string $message
     */
    public function content(string $message): self
    {
        $this->payload['text'] = $message;

        return $this;
    }

    /**
     * Set recipient phone number.
     *
     * @param string $to
     */
    public function to(string $to): self
    {
        $this->payload['to'] = $to;

        return $this;
    }

    /**
     * Set sender name.
     *
     * @param string $from
     */
    public function from(string $from): self
    {
        $this->payload['from'] = $from;

        return $this;
    }

    /**
     * Set notification delay.
     *
     * @param string $timestamp
     */
    public function delay(string $timestamp): self
    {
        $this->payload['delay'] = $timestamp;

        return $this;
    }

    /**
     * Disable reload lock.
     */
    public function noReload(): self
    {
        $this->payload['no_reload'] = 1;

        return $this;
    }

    /**
     * Activate debug mode.
     */
    public function debug(): self
    {
        $this->payload['debug'] = 1;

        return $this;
    }

    /**
     * Set encoding to unicode.
     */
    public function unicode(): self
    {
        $this->payload['unicode'] = 1;

        return $this;
    }

    /**
     * SMS is sent as flash message.
     */
    public function flash(): self
    {
        $this->payload['flash'] = 1;

        return $this;
    }

    /**
     * The API returns more details about the SMS sent.
     */
    public function details(): self
    {
        $this->payload['details'] = 1;

        return $this;
    }
}
