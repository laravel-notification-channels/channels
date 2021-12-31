<?php

declare(strict_types=1);

namespace NotificationChannels\WXWork\Exceptions;

final class CouldNotSendNotification extends \Exception
{
    public function __construct(string $message, ?int $code = null)
    {
        $this->message = $message;
        $this->code = $code;

        parent::__construct($message, $code);
    }

    public static function wxworkRespondedWithAnError(
        string $message,
        ?int $code = null
    ): self {
        return new self(
            sprintf('wxwork responded with an error: `%s`', $message),
            $code
        );
    }
}
