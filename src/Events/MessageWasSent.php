<?php
/**
 * Author: Hilmi Erdem KEREN
 * Date: 17/11/2016.
 */
namespace NotificationChannels\JetSMS\Events;

use NotificationChannels\JetSMS\JetSMSMessageInterface;
use NotificationChannels\JetSMS\Clients\JetSMSApiResponseInterface;

/**
 * Class MessageWasSent.
 */
class MessageWasSent
{
    /**
     * The sms message.
     *
     * @var JetSMSMessageInterface
     */
    public $message;

    /**
     * The Api response.
     *
     * @var JetSMSApiResponseInterface
     */
    public $response;

    /**
     * MessageWasSent constructor.
     *
     * @param JetSMSMessageInterface     $message
     * @param JetSMSApiResponseInterface $response
     */
    public function __construct(JetSMSMessageInterface $message, JetSMSApiResponseInterface $response)
    {
        $this->message = $message;
        $this->response = $response;
    }
}
