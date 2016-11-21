<?php

namespace NotificationChannels\ClickSend;

use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;

class ClicksendChannel
{
    /**
     * @var Clicksend
     */
    protected $clicksend;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * ClicksendChannel constructor.
     *
     * @param Clicksend $clicksend
     * @param Dispatcher $events
     */
    public function __construct(Clicksend $clicksend, Dispatcher $events)
    {
        $this->clicksend = $clicksend;
        $this->events = $events;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return mixed
     */
    public function send($notifiable, Notification $notification)
    {
        try {
            $to = $this->getTo($notifiable);
            $message = $notification->toClicksend($notifiable);

            if (is_string($message)) {
                $message = new ClicksendSmsMessage($message);
            }

            if (!$message instanceof ClicksendMessage) {
                throw new Exception('Unable to send SMS. Please try again.');
            }

            return $this->clicksend->sendMessage($message, $to);

        } catch (Exception $exception) {

            $this->events->fire(
                new NotificationFailed($notifiable, $notification, 'clicksend', ['message' => $exception->getMessage()])
            );

        }
    }

    /**
     * Get to number.
     *
     * @param $notifiable
     * @return mixed
     * @throws Exception
     */
    protected function getTo($notifiable)
    {
        if ($notifiable->routeNotificationFor('clicksend')) {
            return $notifiable->routeNotificationFor('clicksend');
        }
        if (isset($notifiable->phone)) {
            return $notifiable->phone;
        }

        throw new Exception('Invalid receiver number. Please try again.');
    }
}
