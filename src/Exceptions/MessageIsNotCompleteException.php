<?php

namespace FtwSoft\NotificationChannels\Intercom\Exceptions;

use Throwable;
use FtwSoft\NotificationChannels\Intercom\IntercomMessage;

class MessageIsNotCompleteException extends IntercomException
{
    /**
     * @var IntercomMessage
     */
    private $intercomMessage;

    /**
     * MessageIsNotCompleteException constructor.
     *
     * @param IntercomMessage $intercomMessage
     * @param string          $message
     * @param int             $code
     * @param Throwable|null  $previous
     */
    public function __construct(
        IntercomMessage $intercomMessage,
        string $message = '',
        int $code = 0,
        Throwable $previous = null
    ) {
        $this->intercomMessage = $intercomMessage;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return IntercomMessage
     */
    public function getIntercomMessage(): IntercomMessage
    {
        return $this->intercomMessage;
    }
}
