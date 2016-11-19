<?php
/**
 * Author: Hilmi Erdem KEREN
 * Date: 17/11/2016.
 */
namespace NotificationChannels\JetSMS\Clients;

use NotificationChannels\JetSMS\Exceptions\CouldNotSendNotification;
use NotificationChannels\JetSMS\JetSMSMessageInterface;

/**
 * Interface JetSMSClientInterface.
 */
interface JetSMSClientInterface
{
    /**
     * Send the client request to the service.
     *
     * @throws CouldNotSendNotification If SMS Api returns false.
     * @return JetSMSApiResponseInterface
     */
    public function sendRequest();

    /**
     * Add an sms message to request.
     *
     * @param  JetSMSMessageInterface $smsMessage
     * @return void
     */
    public function addToRequest(JetSMSMessageInterface $smsMessage);
}
