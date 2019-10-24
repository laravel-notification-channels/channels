<?php

namespace NotificationChannels\MoceanApi;

use Mocean\Message\Message;
use NotificationChannels\MoceanApi\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;
use Mocean\Laravel\Manager as MoceanClient;

class MoceanApiChannel
{
    /**
     * @var MoceanClient
     */
    protected $mocean;

    /**
     * MoceanApiChannel constructor.
     *
     * @param MoceanClient $mocean
     */
    public function __construct(MoceanClient $mocean)
    {
        $this->mocean = $mocean;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return mixed
     * @throws CouldNotSendNotification
     * @throws \Mocean\Client\Exception\Exception
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$to = $notifiable->routeNotificationFor('moceanapi', $notification)) {
            throw CouldNotSendNotification::invalidReceiver();
        }

        $message = $notification->toMoceanapi($notifiable);

        if (is_string($message)) {
            $message = new MoceanApiSmsMessage('MoceanApi', $to, $message);
        } else if (is_array($message)) {
            $message = $this->createMessageFromArray($message, $to);
        }

        if (!$message instanceof Message) {
            throw CouldNotSendNotification::invalidMessageObject($message);
        }

        return $this->mocean->message()->send($message);
    }

    /**
     * Create MoceanApiSmsMessage from array
     *
     * @param $message
     * @return MoceanApiSmsMessage
     */
    protected function createMessageFromArray($message, $to)
    {
        $message = collect($message);

        return new MoceanApiSmsMessage(
            $message->get('mocean-from', 'MoceanApi'),
            $message->get('mocean-to', $to),
            $message->get('mocean-text'),
            $message->except(['mocean-from', 'mocean-to', 'mocean-text'])->toArray()
        );
    }
}
