<?php

namespace NotificationChannels\Sailthru\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use NotificationChannels\Sailthru\SailthruMessage;

/**
 * Class MessageFailedToSend
 */
class MessageFailedToSend
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var SailthruMessage
     */
    protected $sailthruMessage;

    /**
     * @var
     */
    protected $exception;

    /**
     * MessageWasSent constructor.
     *
     * @param SailthruMessage $sailthruMessage
     * @param $exception
     */
    public function __construct(SailthruMessage $sailthruMessage, \Sailthru_Client_Exception $exception)
    {
        $this->sailthruMessage = $sailthruMessage;
        $this->exception = $exception;
    }
}
