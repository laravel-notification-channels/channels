<?php
/**
 * Author: Hilmi Erdem KEREN
 * Date: 18/11/2016
 * Time: 13:09.
 */
namespace NotificationChannels\JetSMS\Exceptions;

/**
 * Class CouldNotBootClient.
 */
class CouldNotBootClient extends \Exception
{
    /**
     * Get a new could not boot client exception.
     *
     * @return static
     */
    public static function missingCredentials()
    {
        $message = 'The JetSMS channel will not boot without API credentials.';

        return new static($message);
    }
}
