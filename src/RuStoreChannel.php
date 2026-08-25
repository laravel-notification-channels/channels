<?php

declare(strict_types=1);

namespace NotificationChannels\RuStore;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use NotificationChannels\RuStore\Exceptions\RuStorePushNotingSentException;
use NotificationChannels\RuStore\Reports\RuStoreReport;

class RuStoreChannel
{
    /**
     * @param  Dispatcher  $events
     * @param  RuStoreClient  $client
     */
    public function __construct(protected Dispatcher $events, private readonly RuStoreClient $client)
    {
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     * @return RuStoreReport
     *
     * @throws RuStorePushNotingSentException
     */
    public function send(mixed $notifiable, Notification $notification): RuStoreReport
    {
        $message = $notification->toRuStore($notifiable);
        $tokens = Arr::wrap($notifiable->routeNotificationForRuStore());
        $report = $this->client->send($message, $tokens);
        $this->dispatchFailedNotification($notifiable, $notification, $report->getFailure());

        return $report->getSuccess();
    }

    /**
     * Поджигание события NotificationFailed.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     * @param  RuStoreReport  $report
     * @return void
     */
    private function dispatchFailedNotification(mixed $notifiable, Notification $notification, RuStoreReport $report): void
    {
        if ($report->all()->isNotEmpty()) {
            $this->events->dispatch(new NotificationFailed($notifiable, $notification, self::class, [
                'report' => $report,
            ]));
        }
    }
}
