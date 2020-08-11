<?php

namespace NotificationChannels\Signal;

use Illuminate\Support\Arr;

class SignalMessage
{
    /**
    * The phone number messages will be sent from.
    * Must include prefix ("+") and country code.
    *
    * @var string
    **/
    public $username;

    /**
    * The message content.
    *
    * @var string
    **/
    public $message;

    /**
     * Create a new message instance.
     *
     * @param  string $message
     *
     * @return static
     */
    public static function create($message = '')
    {
        return new static($message);
    }

    /**
     * Create a new message instance.
     *
     * @param  string  $message
     */
    public function __construct($message = '')
    {
        $this->message = $message;
    }

    /**
     * Set the message.
     *
     * @param  string  $message
     *
     * @return $this
     */
    public function message($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Set the phone number the message should be sent to.
     *
     * @param  string  $recipient
     *
     * @return $this
     */
    public function recipient($recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
    *
    * The phone number of the recipient.
    * Must include prefix ("+") and country code.
    *
    * @var string
    **/
    public $recipient;
}
