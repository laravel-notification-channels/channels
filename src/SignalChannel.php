<?php

namespace NotificationChannels\Signal;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use NotificationChannels\Signal\Exceptions\CouldNotSendNotification;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class SignalChannel
{
    /**
    * Signal instance
    * @var Signal
    **/
    protected $signal;

    /**
    * Send the given notification.
    *
    * @param mixed $notifiable
    * @param \Illuminate\Notifications\Notification $notification
    *
    * @throws \NotificationChannels\Signal\Exceptions\CouldNotSendNotification
    */

    public function send($notifiable, Notification $notification)
    {
        $collection = collect($notification->toSignal($notifiable));

        $recipient = $collection->get('recipient');
        $message = $collection->get('message');

        //Run signal-cli via Symfony Process.
        $result = new Process(
            [config('signal-notification-channel.signal_cli'),
            '--username',config('signal-notification-channel.username'),
            'send','--message',$message,
            $recipient],
            //Pass JAVA_HOME to Symfony so signal-cli can run.
            null,
            ['JAVA_HOME' => config('signal-notification-channel.java_location')]
        );

        $result->run();

        if (!$result->isSuccessful()) {
          throw new ProcessFailedException($result);
        }

        return $result;
    }
}
