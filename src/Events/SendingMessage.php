<?php
/**
 * Author: Hilmi Erdem KEREN
 * Date: 17/11/2016.
 */
namespace NotificationChannels\JetSMS\Events;

use NotificationChannels\JetSMS\JetSMSMessageInterface;

/**
 * Class SendingMessage.
 */
class SendingMessage
{
    /**
     * The JetSMS message.
     *
     * @var JetSMSMessageInterface
     */
    public $message;

    /**
     * SendingMessage constructor.
     *
     * @param $message
     */
    public function __construct(JetSMSMessageInterface $message)
    {
        $this->message = $message;
    }
}
