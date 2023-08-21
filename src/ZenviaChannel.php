<?php

namespace NotificationChannels\LaravelZenviaChannel;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use NotificationChannels\LaravelZenviaChannel\Exceptions\CouldNotSendNotification;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Exception;

class ZenviaChannel
{
    /**
     * @var Zenvia
     */
    protected $zenvia;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * TwilioChannel constructor.
     *
     * @param Zenvia $zenvia
     * @param Dispatcher $events
     */
    public function __construct(Zenvia $zenvia, Dispatcher $events)
    {
        $this->zenvia = $zenvia;
        $this->events = $events;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @return mixed
     * @throws Exception
     */
    public function send($notifiable, Notification $notification)
    {
        try {
            $to = $this->getTo($notifiable, $notification);
            $message = $notification->toZenvia($notifiable);

            if (is_string($message)) {
                $message = new ZenviaSmsMessage($message);
            }

            if (! $message instanceof ZenviaMessage) {
                throw CouldNotSendNotification::invalidMessageObject($message);
            }

            return $this->zenvia->sendMessage($message, $to);
        } catch(ConnectException $exception) {
            $this->notificationEventFailed($notifiable, $notification, $exception);

            throw CouldNotSendNotification::serviceConnectionError();
        } catch(ClientException $exception) {
            $this->notificationEventFailed($notifiable, $notification, $exception);

            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        } catch(Exception $exception) {
            $this->notificationEventFailed($notifiable, $notification, $exception);

            throw $exception;
        }
    }

    /**
     * Get the address to send a notification to.
     *
     * @param mixed $notifiable
     * @param Notification|null $notification
     *
     * @return mixed
     * @throws CouldNotSendNotification
     */
    protected function getTo($notifiable, $notification = null)
    {
        if ($notifiable->routeNotificationFor(self::class, $notification)) {
            return $notifiable->routeNotificationFor(self::class, $notification);
        }
        if ($notifiable->routeNotificationFor('zenvia', $notification)) {
            return $notifiable->routeNotificationFor('zenvia', $notification);
        }
        if (isset($notifiable->phone_number)) {
            return $notifiable->phone_number;
        }

        throw CouldNotSendNotification::invalidReceiver();
    }

    protected function notificationEventFailed($notifiable, Notification $notification, Exception $exception)
    {
        $event = new NotificationFailed(
            $notifiable,
            $notification,
            'zenvia',
            ['message' => $exception->getMessage(), 'exception' => $exception]
        );

        $this->events->dispatch($event);
    }
}
