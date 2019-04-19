<?php

namespace NotificationChannels\ExpoPushNotifications;

use ExponentPhpSDK\Expo;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Events\Dispatcher;
use ExponentPhpSDK\Exceptions\ExpoException;
use Illuminate\Notifications\Events\NotificationFailed;
use NotificationChannels\ExpoPushNotifications\Exceptions\CouldNotSendNotification;

class ExpoChannel
{
    /**
     * @var Dispatcher
     */
    private $events;

    /**
     * @var Expo
     */
    public $expo;

    /**
     * ExpoChannel constructor.
     *
     * @param Expo $expo
     * @param Dispatcher $events
     */
    public function __construct(Expo $expo, Dispatcher $events)
    {
        $this->events = $events;
        $this->expo = $expo;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws CouldNotSendNotification
     *
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $interest = $notifiable->routeNotificationFor('ExpoPushNotifications')
            ?: $this->interestName($notifiable);

        try {
            $this->expo->notify(
                $interest,
                $notification->toExpoPush($notifiable)->toArray(),
                true
            );
        } catch (ExpoException $e) {
            $this->events->dispatch(
                new NotificationFailed($notifiable, $notification, 'expo-push-notifications', $e->getMessage())
            );
        }
    }

    /**
     * Get the interest name for the notifiable.
     *
     * @param $notifiable
     *
     * @return string
     */
    public function interestName($notifiable)
    {
        $class = str_replace('\\', '.', get_class($notifiable));

        return $class.'.'.$notifiable->getKey();
    }
}
