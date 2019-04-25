<?php

namespace NotificationChannels\Sailthru;

use Illuminate\Notifications\Notification;
use NotificationChannels\Sailthru\Events\MessageFailedToSend;
use NotificationChannels\Sailthru\Events\MessageWasSent;
use Sailthru_Client_Exception;

class SailthruChannel
{
    /**
     * SailthruChannel constructor.
     *
     * @param \Sailthru_Client $sailthru
     */
    public function __construct(\Sailthru_Client $sailthru)
    {
        $this->sailthru = $sailthru;
    }

    /**
     * @param $notifiable
     * @param Notification $notification
     *
     * @return array
     */
    public function send($notifiable, Notification $notification)
    {
        try {

            /** @var SailthruMessage $message */
            $message = $notification->toSailthru($notifiable);

            if (method_exists($notifiable, 'sailthruDefaultVars')) {
                $message->mergeDefaultVars($notifiable->sailthruDefaultVars());
            }

            $response = $message->isMultiSend()
                ? $this->multiSend($message)
                : $this->singleSend($message);

            event(new MessageWasSent($message, $response));

            return $response;
        } catch (Sailthru_Client_Exception $e) {
            event(new MessageFailedToSend($message, $e));
        }
    }

    /**
     * @param SailthruMessage $sailthruMessage
     *
     * @throws Sailthru_Client_Exception
     *
     * @return array
     */
    protected function multiSend(SailthruMessage $sailthruMessage)
    {
        return $this->sailthru->multisend(
            $sailthruMessage->getTemplate(),
            $sailthruMessage->getToEmail(),
            $sailthruMessage->getVars(),
            $sailthruMessage->getEVars(),
            $sailthruMessage->getOptions()
        );
    }

    /**
     * @param SailthruMessage $sailthruMessage
     *
     * @throws Sailthru_Client_Exception
     *
     * @return array
     */
    protected function singleSend(SailthruMessage $sailthruMessage)
    {
        return $this->sailthru->send(
            $sailthruMessage->getTemplate(),
            $sailthruMessage->getToEmail(),
            $sailthruMessage->getVars(),
            $sailthruMessage->getOptions()
        );
    }
}
