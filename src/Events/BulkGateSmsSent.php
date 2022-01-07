<?php

namespace NotificationChannels\BulkGate\Events;

class BulkGateSmsSent
{
    /**
     * @var mixed
     */
    public $notifiable;
    /**
     * @var \Illuminate\Notifications\Notification
     */
    public $notification;
    /**
     * @var \BulkGate\Message\Response
     */
    public $response;

    /**
     * @param  mixed  $notifiable
     */
    public function __construct(
        $notifiable,
        \Illuminate\Notifications\Notification $notification,
        \BulkGate\Message\Response $response
    ) {
        $this->notifiable = $notifiable;
        $this->notification = $notification;
        $this->response = $response;
    }
}
