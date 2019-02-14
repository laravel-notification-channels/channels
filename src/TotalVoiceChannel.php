<?php

namespace NotificationChannels\TotalVoice;

use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use NotificationChannels\TotalVoice\Exceptions\CouldNotSendNotification;

class TotalVoiceChannel
{
    /**
     * @var TotalVoice
     */
    protected $totalvoice;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * TotalVoiceChannel constructor.
     *
     * @param TotalVoice $totalvoice
     * @param Dispatcher $events
     */
    public function __construct(TotalVoice $totalvoice, Dispatcher $events)
    {
        $this->totalvoice = $totalvoice;
        $this->events = $events;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return mixed
     * @throws \NotificationChannels\TotalVoice\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        try {
            $to = $this->getTo($notifiable);
            $message = $notification->toTotalVoice($notifiable);

            if (is_string($message)) {
                $message = new TotalVoiceSmsMessage($message);
            }

            if (! $message instanceof TotalVoiceMessage) {
                throw CouldNotSendNotification::invalidMessageObject($message);
            }

            return $this->totalvoice->sendMessage($message, $to);
        } catch (Exception $exception) {
            $event = new NotificationFailed($notifiable, $notification, 'totalvoice', ['message' => $exception->getMessage(), 'exception' => $exception]);
            if (function_exists('event')) {
                event($event);
            } elseif (method_exists($this->events, 'fire')) {
                $this->events->fire($event);
            }
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
        if ($notifiable->routeNotificationFor('totalVoice')) {
            return $notifiable->routeNotificationFor('totalVoice');
        }
        if (isset($notifiable->phone_number)) {
            return $notifiable->phone_number;
        }
        throw CouldNotSendNotification::invalidReceiver();
    }
}
