<?php

namespace NotificationChannels\SMS77;

/**
 * Class SMS77Message.
 */
class SMS77Message
{
    protected $payload = [];

    /**
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->payload['text'] = $message;
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
    public function toIsset(): bool
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
     * If set to true, no SMS is being sent.
     * 
     * @param int $debug
     */
    public function debug(bool $debug): self
    {
        $this->payload['debug'] = (int) $debug;
        return $this;
    }

    /**
     * If set to true, SMS is sent as unifcode.
     * 
     * @param int $unicode
     */
    public function unicode(bool $unicode): self
    {
        $this->payload['unicode'] = (int) $unicode;
        return $this;
    }

    /**
     * If set to true, SMS is sent as flash message.
     * 
     * @param int $flash
     */
    public function flash(bool $flash): self
    {
        $this->payload['flash'] = (int) $flash;
        return $this;
    }

    /**
     * The API returns more details about the SMS sent.
     * 
     * @param int $details
     */
    public function details(bool $details): self
    {
        $this->payload['details'] = (int) $details;
        return $this;
    }

    /**
     * The API response is json formatted.
     * 
     * @param int $json
     */
    public function json(bool $json): self
    {
        $this->payload['json'] = (int) $json;
        return $this;
    }
}
