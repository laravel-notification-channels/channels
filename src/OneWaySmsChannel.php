<?php

namespace NotificationChannels\OneWaySms;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use NotificationChannels\OneWaySms\Exceptions\CouldNotSendNotification;

class OneWaySmsChannel
{
    /** The gateway accepts at most 10 recipients per request. */
    public const MAX_RECIPIENTS = 10;

    public function __construct(
        protected OneWaySmsApi $api,
        protected ?string $sender = null,
    ) {
    }

    /**
     * Send the given notification.
     *
     * @return array<int, string>|null the MT IDs, or null when the notifiable
     *                                 has no OneWaySMS route
     *
     * @throws CouldNotSendNotification
     */
    public function send(mixed $notifiable, Notification $notification): ?array
    {
        $to = $notifiable->routeNotificationFor('one_way_sms', $notification);

        if (empty($to)) {
            return null;
        }

        $recipients = array_values(array_filter(Arr::wrap($to)));

        if ($recipients === []) {
            return null;
        }

        if (count($recipients) > self::MAX_RECIPIENTS) {
            throw CouldNotSendNotification::tooManyRecipients(count($recipients), self::MAX_RECIPIENTS);
        }

        /** @var OneWaySmsMessage|string $message */
        $message = $notification->toOneWaySms($notifiable); // @phpstan-ignore-line

        if (is_string($message)) {
            $message = new OneWaySmsMessage($message);
        }

        if (trim($message->content) === '') {
            throw CouldNotSendNotification::emptyMessage();
        }

        $sender = $message->sender ?: $this->sender;

        if (empty($sender)) {
            throw CouldNotSendNotification::missingSender();
        }

        return $this->api->send([
            'to' => implode(',', $recipients),
            'sender' => $sender,
            'languagetype' => $message->languageType(),
            'message' => $message->encodedContent(),
        ]);
    }
}
