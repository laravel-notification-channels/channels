<?php

namespace NotificationChannels\AwsSns;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use NotificationChannels\AwsSns\Exceptions\CouldNotSendNotification;

class SnsChannel
{
    /**
     * @var Sns
     */
    protected $sns;

    /**
     * @var Dispatcher
     */
    protected $events;

    public function __construct(Sns $sns, Dispatcher $events)
    {
        $this->sns = $sns;
        $this->events = $events;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed                                  $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return \Aws\Result
     */
    public function send($notifiable, Notification $notification)
    {
        try {
            $destination = $this->getDestination($notifiable);
            $message = $this->getMessage($notifiable, $notification);

            return $this->sns->send($message, $destination);
        } catch (\Exception $e) {
            $event = new NotificationFailed(
                $notifiable,
                $notification,
                'sns',
                ['message' => $e->getMessage(), 'exception' => $e]
            );
            $this->events->dispatch($event);
        }
    }

    /**
     * Get the phone number to send a notification to.
     *
     * @param $notifiable
     * @return mixed
     * @throws CouldNotSendNotification
     */
    protected function getDestination($notifiable)
    {
        if ($to = $notifiable->routeNotificationFor('sns')) {
            return $to;
        }

        return $this->guessDestination($notifiable);
    }

    /**
     * Try to get the phone number from some commonly used attributes for that.
     *
     * @param $notifiable
     * @return mixed
     * @throws CouldNotSendNotification
     */
    protected function guessDestination($notifiable)
    {
        $commonAttributes = ['phone', 'phone_number', 'full_phone'];
        foreach ($commonAttributes as $attribute) {
            if (isset($notifiable->$attribute)) {
                return $notifiable->$attribute;
            }
        }

        throw CouldNotSendNotification::invalidReceiver();
    }

    /**
     * Get the SNS Message object.
     *
     * @param $notifiable
     * @param  Notification             $notification
     * @return SnsMessage
     * @throws CouldNotSendNotification
     */
    protected function getMessage($notifiable, Notification $notification): SnsMessage
    {
        $message = $notification->toSns($notifiable);
        if (is_string($message)) {
            return new SnsMessage($message);
        }

        if ($message instanceof SnsMessage) {
            return $message;
        }

        throw CouldNotSendNotification::invalidMessageObject($message);
    }
}
