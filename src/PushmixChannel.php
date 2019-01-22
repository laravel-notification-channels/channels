<?php

namespace NotificationChannels\Pushmix;

use NotificationChannels\Pushmix\PushmixClient;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Notifications\Notification;
use GuzzleHttp\Exception\RequestException;
use NotificationChannels\Pushmix\Exceptions\CouldNotSendNotification;

class PushmixChannel
{
    /** @var PushmixClient */
    protected $pusmixClient;

    public function __construct(PushmixClient $pusmixClient)
    {
        $this->pusmixClient = $pusmixClient;
    }

    /**
     * Send Notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \NotificationChannels\Pushmix\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('Pushmix') ) {
            return;
        }

        $parameters = $notification->toPushmix($to)->toArray();

        // initialize subscription key
        $this->pusmixClient->initKey();

        // Call with parameters
        try{
            return $this->pusmixClient->sendNotification($parameters);

        } catch (RequestException $e) {

          throw CouldNotSendNotification::error($e);
      }

    }
    /***/


}
