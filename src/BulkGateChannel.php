<?php

namespace NotificationChannels\BulkGate;

use BulkGate\Sms\Sender;
use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use NotificationChannels\BulkGate\Events\BulkGateSmsSent;
use NotificationChannels\BulkGate\Exceptions\CouldNotSendNotification;

class BulkGateChannel
{
    /**
     * @var \BulkGate\Sms\Sender
     */
    protected $sender;
    /**
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $dispatcher;

    public function __construct(Sender $sender, Dispatcher $dispatcher)
    {
        $this->sender = $sender;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     *
     * @throws \NotificationChannels\BulkGate\Exceptions\CouldNotSendNotification
     * @throws \Exception
     */
    public function send($notifiable, Notification $notification): \BulkGate\Message\Response
    {
        if (! method_exists($notification, 'toBulkGate')) {
            $exception = CouldNotSendNotification::undefinedMethod($notification);

            $this->notifyFail($notifiable, $notification, $exception);

            throw $exception;
        }

        /** @var \NotificationChannels\BulkGate\BulkGateMessage $message */
        if (! ($message = $notification->toBulkGate($notification)) instanceof BulkGateMessage) {
            $exception = CouldNotSendNotification::invalidMessage($message);

            $this->notifyFail($notifiable, $notification, $exception);

            throw $exception;
        }

        if (! $message->hasPhoneNumber()) {
            $message->to($this->getTo($notifiable));
        }

        try {
            $response = $this->sender->send($message->getMessage());

            if (! $response->isSuccess()) {
                throw CouldNotSendNotification::serviceRespondedWithAnError($response);
            }

            $event = new BulkGateSmsSent($notifiable, $notification, $response);
            $this->dispatcher->dispatch($event);

            return $response;
        } catch (Exception $exception) {
            $this->notifyFail($notifiable, $notification, $exception);

            throw $exception;
        }
    }

    /**
     * @param  mixed  $notifiable
     * @param  null|mixed  $notification
     *
     * @throws \NotificationChannels\BulkGate\Exceptions\CouldNotSendNotification
     */
    protected function getTo($notifiable, $notification = null)
    {
        if ($notifiable->routeNotificationFor(self::class, $notification)) {
            return $notifiable->routeNotificationFor(self::class, $notification);
        }
        if ($notifiable->routeNotificationFor('bulkgate', $notification)) {
            return $notifiable->routeNotificationFor('bulkgate', $notification);
        }
        if (isset($notifiable->phone_number)) {
            return $notifiable->phone_number;
        }

        throw CouldNotSendNotification::invalidReceiver();
    }

    protected function notifyFail($notifiable, $notification, $exception)
    {
        $event = new NotificationFailed(
            $notifiable,
            $notification,
            'bulkgate',
            ['message' => $exception->getMessage(), 'exception' => $exception]
        );

        $this->dispatcher->dispatch($event);
    }
}
