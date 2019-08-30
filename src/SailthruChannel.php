<?php

namespace NotificationChannels\Sailthru;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use NotificationChannels\Sailthru\Events\MessageFailedToSend;
use NotificationChannels\Sailthru\Events\MessageWasSent;

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
     * Get default variables that are defined for all emails.
     *
     * Override this to use a different strategy.
     *
     * @return array
     */
    public static function getDefaultVars(): array
    {
        return [];
    }

    /**
     * @param $notifiable
     * @param Notification $notification
     *
     * @return array
     */
    public function send($notifiable, Notification $notification)
    {
        if (!config('services.sailthru.enabled', true)) {
            Log::info(
                'Sending Sailthru message',
                [
                    'notifiable' => $notifiable,
                    'notification' => $notification,
                ]
            );

            return [];
        }

        try {
            /** @var SailthruMessage $message */
            $message = $notification->toSailthru($notifiable);
            $message->mergeDefaultVars(self::getDefaultVars());

            $response = $message->isMultiSend()
                ? $this->multiSend($message)
                : $this->singleSend($message);

            event(new MessageWasSent($message, $response));

            return $response;
        } catch (\Sailthru_Client_Exception $e) {
            event(new MessageFailedToSend($message, $e));

            return [];
        }
    }

    /**
     * @param SailthruMessage $sailthruMessage
     *
     * @throws \Sailthru_Client_Exception
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
     * @return array
     */
    protected function singleSend(SailthruMessage $sailthruMessage)
    {
        if (config('services.sailthru.log_payload', false)) {
            Log::debug(
                'Sailthru Payload',
                [
                    'template' => $sailthruMessage->getTemplate(),
                    'email' => $sailthruMessage->getToEmail(),
                    'vars' => $sailthruMessage->getVars(),
                    'options' => $sailthruMessage->getOptions()
                ]
            );
        }


        return $this->sailthru->send(
            $sailthruMessage->getTemplate(),
            $sailthruMessage->getToEmail(),
            $sailthruMessage->getVars(),
            $sailthruMessage->getOptions()
        );
    }
}
