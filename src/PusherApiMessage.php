<?php

namespace NotificationChannels\PusherApiNotifications;

class PusherApiMessage
{
    /** @var string $channels */
    protected $channels = null;

    /** @var string $event */
    protected $event = null;

    /** @var array|string $data */
    protected $data = null;

    /** @var string $socketId */
    protected $socketId = null;

    /** @var bool $debug */
    protected $debug = false;

    /** @var bool $alreadyEncoded */
    protected $alreadyEncoded = false;

    /**
     * Creates the instance.
     *
     * @param array|string $channels
     * @param string $event
     * @param mixed $data
     * @param string|null $socketId
     * @param bool $debug
     * @param bool $alreadyEncoded
     */
    public function __construct(
        $channels = null,
        $event = null,
        $data = null,
        $socketId = null,
        $debug = false,
        $alreadyEncoded = false
    ) {
        $this->channels = $channels;
        $this->event = $event;
        $this->data = $data;
        $this->socketId = $socketId;
        $this->debug = $debug;
        $this->alreadyEncoded = $alreadyEncoded;
    }

    /**
     * A channel name or an array of channel names to publish the event on.
     *
     * @param array|string $channels
     * @return  $this
     */
    public function channels($channels)
    {
        $this->channels = $channels;

        return $this;
    }

    /**
     * Pusher event.
     *
     * @param string $event
     * @return  $this
     */
    public function event($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Event data.
     *
     * @param mixed $data
     * @return  $this
     */
    public function data($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * [optional].
     *
     * @param string|null $socketId
     * @return  $this
     */
    public function socketId($socketId = null)
    {
        $this->socketId = $socketId;

        return $this;
    }

    /**
     * [optional].
     *
     * @param bool $debug
     * @return  $this
     */
    public function debug($debug = false)
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * [optional].
     *
     * @param bool $alreadyEncoded
     * @return  $this
     */
    public function alreadyEncoded($alreadyEncoded = false)
    {
        $this->alreadyEncoded = $alreadyEncoded;

        return $this;
    }

    public function toArray()
    {
        return [
            'channels' => $this->channels,
            'event' => $this->event,
            'data' => $this->data,
            'socketId' => $this->socketId,
            'debug' => $this->debug,
            'alreadyEncoded' => $this->alreadyEncoded,
        ];
    }
}
