<?php

namespace NotificationChannels\ClickSend;

use ClickSendLib\Controllers\SMSController;

class Clicksend
{
    /**
     * @var SMSController
     */
    protected $clicksendSmsController;

    /**
     * Clicksend constructor.
     *
     * @param SMSController $clicksendSmsController
     */
    public function __construct(SMSController $clicksendSmsController)
    {
        $this->clicksendSmsController = $clicksendSmsController;
    }

    /**
     * Send a Message.
     *
     * @param ClicksendMessage $message
     * @param $to
     * @return string
     * @throws \Exception
     */
    public function sendMessage(ClicksendMessage $message, $to)
    {
        if ($message instanceof ClicksendSmsMessage) {
            return $this->sendSmsMessage($message, $to);
        }

        throw new \Exception('Unable to send message.');
    }

    /**
     * Send SMS message.
     *
     * @param $message
     * @param $to
     * @return string
     * @throws \ClickSendLib\APIException
     */
    protected function sendSmsMessage($message, $to)
    {
        $payload = [
            [
                'body' => $message->content,
                'to' => $to,
            ]
        ];

        return $this->clicksendSmsController->sendSms(['messages' => $payload]);
    }
}
