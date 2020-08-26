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
    * @return static string
    */
    public static function create(string $message = '')
    {
      return new static(string $message);
    }

    /**
    * Create a new message instance.
    *
    * @param  string  $message
    */
    public function __construct(string $message = '')
    {
      $this->message = string $message;
    }

    /**
    * Set the message.
    *
    * @param  string  $message
    *
    * @return $this
    */
    public function message(string $message)
    {
      $this->message = string $message;

      return $this;
    }

    /**
    * Set the phone number the message should be sent to.
    *
    * @param  string  $recipient
    *
    * @return $this
    */
    public function recipient(string $recipient)
    {
      $this->recipient = string $recipient;

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
