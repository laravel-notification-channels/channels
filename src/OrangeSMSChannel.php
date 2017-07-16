<?php

namespace NotificationChannels\Orange;

use Mediumart\Orange\SMS\SMS;
use Illuminate\Support\Facades\App;
use Illuminate\Notifications\Notification;

class OrangeSMSChannel
{
    /**
     * @var \Mediumart\Orange\SMS\SMS
     */
    private $client;

    /**
     * OrangeSMSChannel constructor.
     *
     * @param \Mediumart\Orange\SMS\SMS $client
     */
    public function __construct(SMS $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param  $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        return $this->sendMessage($notification->toOrange($notifiable));
    }

    /**
     * Send the given notification message.
     *
     * @param  OrangeMessage $message
     * @return mixed
     */
    public function sendMessage(OrangeMessage $message)
    {
        return $this
            ->client
            ->to($message->to)
            ->from($message->from)
            ->message($message->text)
            ->send();
    }

    /**
     * Check for the channel capacity.
     *
     * @param  string $driver
     * @return bool
     */
    public static function canHandleNotification($driver)
    {
        return in_array($driver, ['orange']);
    }

    /**
     * Create a new driver instance.
     *
     * @param  $driver
     * @return mixed
     */
    public static function createDriver($driver)
    {
        return static::canHandleNotification($driver)
            ? new static(App::make('orange-sms')) : null;
    }
}
