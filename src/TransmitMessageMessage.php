<?php

namespace NotificationChannels\TransmitMessage;

class TransmitMessageMessage
{
    public $sender = '';
    public $recipient = null;
    public $message = null;

    public function __construct($message = '')
    {
        if (! empty($message)) {
            $this->message = $message;
        }
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function setSender($sender)
    {
        $this->sender = $sender;

        return $this;
    }
}
