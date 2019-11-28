<?php

namespace NotificationChannels\Notify;

use Exception;
use Illuminate\Events\Dispatcher;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Events\NotificationFailed;
use NotificationChannels\Notify\Exceptions\InvalidMessageObject;

class NotifyChannel
{
    /** @var \NotificationChannels\Notify\NotifyClient */
    protected $client;

    /** @var Dispatcher */
    protected $events;

    public function __construct(NotifyClient $client, Dispatcher $events)
    {
        $this->client = $client;
        $this->events = $events;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @throws \NotificationChannels\notify\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        try {
            /** @var $message \NotificationChannels\Notify\NotifyMessage */
            $message = $notification->toNotify($notifiable);
            if ($to = $this->getTo($notifiable)) {
                $message->addRecipient($to['name'], $to['recipient']);
            }

            return $this->client->send($message);
        } catch (Exception $exception) {
            $event = new NotificationFailed(
                $notifiable,
                $notification,
                'notify',
                ['message' => $exception->getMessage(), 'exception' => $exception]
            );
            $this->events->fire($event);
        }
    }

    /**
     * Get the address to send a notification to.
     *
     * @param mixed $notifiable
     * @return mixed
     * @throws CouldNotSendNotification
     */
    protected function getTo($notifiable)
    {
        if ($recipient = $notifiable->routeNotificationFor('notify')) {
            if (is_array($recipient) && array_key_exists('name', $recipient) && array_key_exists('recipient', $recipient)) {
                return $recipient;
            }
            throw InvalidMessageObject::misconfiguredRecipient();
        }
        throw InvalidMessageObject::missingRecipient();
    }
}
