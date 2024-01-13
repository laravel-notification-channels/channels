<?php

namespace NotificationChannels\Pr0gramm;

use Illuminate\Http\Client\RequestException;
use NotificationChannels\Pr0gramm\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;
use NotificationChannels\Pr0gramm\Exceptions\Pr0grammRateLimitReached;
use Tschucki\Pr0grammApi\Facades\Pr0grammApi;

class Pr0grammChannel
{
    public function __construct()
    {
        $loggedIn = Pr0grammApi::loggedIn();
        if (!$loggedIn['loggedIn']) {
            Pr0grammApi::login(config('services.pr0gramm.username'), config('services.pr0gramm.password'));
        }
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Pr0gramm\Exceptions\CouldNotSendNotification
     * @throws RequestException
     * @throws Pr0grammRateLimitReached
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        if (!method_exists($notification, 'toPr0gramm')) {
            throw CouldNotSendNotification::couldNotFindToPr0grammMethod();
        }
        if (!method_exists($notifiable, 'getPr0grammName')) {
            throw CouldNotSendNotification::couldNotFindGetPr0grammNameMethod();
        }

        $message = $notification->toPr0gramm($notifiable);

        if (!is_string($message)) {
            throw CouldNotSendNotification::noStringForMessageProvided();
        }

        $response = Pr0grammApi::Inbox()->post($message, $notifiable->getPr0grammName());

        if ($response->failed()) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($response->body());
        }

        if ($response->status() === 429) {
            throw Pr0grammRateLimitReached::rateLimitReached();
        }

    }
}
