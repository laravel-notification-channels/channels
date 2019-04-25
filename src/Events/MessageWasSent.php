<?php

namespace NotificationChannels\Sailthru\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use NotificationChannels\Sailthru\SailthruMessage;

/**
 * Class MessageWasSent
 */
class MessageWasSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var SailthruMessage
     */
    protected $sailthruMessage;

    /**
     * @var array
     */
    protected $response;

    /**
     * MessageWasSent constructor.
     *
     * @param SailthruMessage $sailthruMessage
     * @param array $response
     */
    public function __construct(SailthruMessage $sailthruMessage, array $response)
    {
        $this->sailthruMessage = $sailthruMessage;
        $this->response = $response;
    }
}
