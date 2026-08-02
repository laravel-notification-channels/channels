<?php

namespace NotificationChannels\SmsIr;

use Farsi\Smsir\Exceptions\SmsirException;
use Farsi\Smsir\Smsir;
use Illuminate\Notifications\Notification;
use NotificationChannels\SmsIr\Exceptions\CouldNotSendNotification;

class SmsIrChannel
{
    /** @var Smsir */
    protected $smsir;

    public function __construct(Smsir $smsir)
    {
        $this->smsir = $smsir;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     *
     * @throws CouldNotSendNotification
     *
     * @return mixed
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $recipients = $this->getRecipients($notifiable, $notification)) {
            // Not an error: a notifiable with no number simply cannot receive this.
            return null;
        }

        if (! method_exists($notification, 'toSmsIr')) {
            throw CouldNotSendNotification::notificationIsMissingTheMessageMethod($notification::class);
        }

        $message = $notification->{'toSmsIr'}($notifiable);

        if (is_string($message)) {
            $message = new SmsIrMessage($message);
        }

        try {
            return $this->sendMessage($recipients, $message);
        } catch (SmsirException $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        }
    }

    /**
     * Get the phone numbers to send to.
     *
     * `routeNotificationForSmsIr()` is preferred. `smsir` is also accepted, so a
     * notifiable already set up for farsi/laravel-smsir keeps working when this
     * channel is added alongside it.
     *
     * @param  mixed  $notifiable
     * @return string[]
     */
    protected function getRecipients($notifiable, Notification $notification)
    {
        $to = $notifiable->routeNotificationFor('smsIr', $notification)
            ?: $notifiable->routeNotificationFor('smsir', $notification);

        if (empty($to)) {
            return [];
        }

        return is_array($to) ? array_values($to) : [$to];
    }

    /**
     * Hand the message to the sms.ir client.
     *
     * A verification message goes one recipient at a time — the endpoint takes a
     * single mobile — while plain text is sent to the whole list in one request.
     *
     * @param  string[]  $recipients
     *
     * @throws CouldNotSendNotification
     *
     * @return mixed
     */
    protected function sendMessage(array $recipients, SmsIrMessage $message)
    {
        if ($message->isVerification()) {
            $results = [];

            foreach ($recipients as $recipient) {
                $results[] = $this->smsir->verify($recipient, $message->template, $message->parameters);
            }

            return count($results) === 1 ? $results[0] : $results;
        }

        if ($message->content === '') {
            throw CouldNotSendNotification::contentIsMissing();
        }

        return $this->smsir->bulk(
            $message->content,
            $recipients,
            $message->sendAt,
            $message->from
        );
    }
}
