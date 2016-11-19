<?php
/**
 * Author: Hilmi Erdem KEREN
 * Date: 17/11/2016.
 */
namespace NotificationChannels\JetSMS\Clients;

/**
 * Interface JetSMSApiResponseInterface.
 */
interface JetSMSApiResponseInterface
{
    /**
     * Get the error code of the JetSMS Api.
     *
     * @return int
     */
    public function errorCode();

    /**
     * Get the error message og the JetSMS Api.
     *
     * @return null|string
     */
    public function errorMessage();

    /**
     * Determine if the api responded with a success or not.
     *
     * @return bool
     */
    public function isSuccess();

    /**
     * Get the message report identifiers for the messages sent.
     * Message report id returns -1 if invalid Msisdns, -2 if invalid message text.
     *
     * @return array
     */
    public function messageReportIdentifiers();
}
