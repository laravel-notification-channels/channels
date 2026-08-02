<?php

namespace NotificationChannels\SmsIr\Exceptions;

use Farsi\Smsir\Exceptions\SmsirException;

class CouldNotSendNotification extends \Exception
{
    /**
     * Thrown when sms.ir rejected the request.
     *
     * The upstream message is carried through unchanged: it is written in
     * Persian and is the most useful thing sms.ir says about a rejection.
     *
     * @return static
     */
    public static function serviceRespondedWithAnError(SmsirException $exception): self
    {
        return new static(
            "sms.ir responded with an error '{$exception->statusCode}: {$exception->getMessage()}'",
            (int) $exception->statusCode,
            $exception
        );
    }

    /**
     * Thrown when the notification is routed here but has no toSmsIr method.
     *
     * @param  string  $notification
     * @return static
     */
    public static function notificationIsMissingTheMessageMethod($notification): self
    {
        return new static(
            "{$notification} is routed to the sms.ir channel but does not define a toSmsIr() method."
        );
    }

    /**
     * Thrown when a plain-text message carries no content.
     *
     * @return static
     */
    public static function contentIsMissing(): self
    {
        return new static(
            'Notification was not sent. A plain-text sms.ir message needs content, '
            .'or a template id via SmsIrMessage::verify().'
        );
    }
}
