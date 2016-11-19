<?php
/**
 * Author: Hilmi Erdem KEREN
 * Date: 17/11/2016.
 */
namespace NotificationChannels\JetSMS\Exceptions;

/**
 * Class CouldNotSendNotification.
 */
class CouldNotSendNotification extends \Exception
{
    /**
     * Get a new could not send notification exception with
     * length error message.
     *
     * @return static
     */
    public static function contentLengthLimitExceeded()
    {
        $message = 'The content length is too long for an sms message.';

        return new static($message);
    }

    /**
     * Get a new could not send notification exception with
     * missing recipient message.
     *
     * @return static
     */
    public static function missingRecipient()
    {
        $message = 'The recipient of the sms message is missing.';

        return new static($message);
    }

    /**
     * Get a new could not send notification exception with
     * missing originator message.
     * @return static
     */
    public static function missingOriginator()
    {
        $message = 'The originator of the sms message is missing';

        return new static($message);
    }

    /**
     * Get a new could not send notification exception with
     * missing Recipient message.
     *
     * @param  string $message
     * @return static
     */
    public static function apiFailed($message)
    {
        return new static($message);
    }
}
