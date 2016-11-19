<?php
/**
 * Author: Hilmi Erdem KEREN
 * Date: 17/11/2016.
 */
namespace NotificationChannels\JetSMS;

/**
 * Interface JetSMSMessageInterface.
 */
interface JetSMSMessageInterface
{
    /**
     * Get the short message.
     *
     * @return string
     */
    public function content();

    /**
     * Get the to number.
     *
     * @return string
     */
    public function number();

    /**
     * Get the outbox name of the message.
     *
     * @return string
     */
    public function originator();

    /**
     * Get the send date of the short message.
     *
     * @return string
     */
    public function sendDate();

    /**
     * Convert the sms message to request sms parameters.
     *
     * @return array
     */
    public function toRequestParams();
}
