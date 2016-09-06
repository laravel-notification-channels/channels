<?php

namespace NotificationChannels\Asterisk;

use Illuminate\Support\Arr;

class AsteriskMessage
{
    /**
     * @var array Params payload.
     */
    public $payload = [
        'device'  => null,
        'number'  => null,
        'message' => null,
    ];

    /**
     * Message constructor.
     *
     * @param string $content
     */
    public function __construct($content = null)
    {
        $this->content($content);
    }

    /**
     * @param string $content
     *
     * @return static
     */
    public static function create($content = null)
    {
        return new static($content);
    }


    /**
     * Notification content
     *
     * @param $content
     *
     * @return $this
     */
    public function content($content)
    {
        $this->payload['message'] = $content;

        return $this;
    }

    /**
     * Set device
     *
     * @param $device
     *
     * @return $this
     */
    public function device($device)
    {
        $this->payload['device'] = $device;

        return $this;
    }

    /**
     * Recipient's phone number.
     *
     * @param $number
     *
     * @return $this
     */
    public function to($number)
    {
        $this->payload['number'] = $number;

        return $this;
    }

    /**
     * Determine if phone number is not given.
     *
     * @return bool
     */
    public function toNotGiven()
    {
        return array_key_exists('number', $this->payload);
    }

    /**
     * Returns params payload.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->payload;
    }
}
