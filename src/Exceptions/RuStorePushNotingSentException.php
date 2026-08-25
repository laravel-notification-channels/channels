<?php

declare(strict_types=1);

namespace NotificationChannels\RuStore\Exceptions;

use Exception;
use Throwable;

class RuStorePushNotingSentException extends Exception
{
    /**
     * @param  string  $message
     * @param  int  $code
     * @param  Throwable|null  $previous
     */
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        $message = $message === '' ? 'Noting sent' : $message;

        parent::__construct($message, $code, $previous);
    }
}
