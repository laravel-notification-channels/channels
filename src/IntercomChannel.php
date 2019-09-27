<?php

namespace FtwSoft\NotificationChannels\Intercom;

use Intercom\IntercomClient;
use Illuminate\Notifications\Notification;
use GuzzleHttp\Exception\BadResponseException;
use FtwSoft\NotificationChannels\Intercom\Exceptions\RequestException;
use FtwSoft\NotificationChannels\Intercom\Contracts\IntercomNotification;
use FtwSoft\NotificationChannels\Intercom\Exceptions\InvalidArgumentException;
use FtwSoft\NotificationChannels\Intercom\Exceptions\MessageIsNotCompleteException;

/**
 * Class IntercomNotificationChannel.
 */
class IntercomChannel
{
    /**
     * @var \Intercom\IntercomClient
     */
    private $client;

    /**
     * IntercomNotificationChannel constructor.
     *
     * @param \Intercom\IntercomClient $client
     */
    public function __construct(IntercomClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification via Intercom API.
     *
     * @param mixed                                  $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \FtwSoft\NotificationChannels\Intercom\Exceptions\RequestException              When server responses with a bad HTTP
     *                                                                                         code
     * @throws \FtwSoft\NotificationChannels\Intercom\Exceptions\MessageIsNotCompleteException When message is not
     *                                                                                         filled correctly
     * @throws \GuzzleHttp\Exception\GuzzleException                                           Other Guzzle uncatched exceptions
     *
     * @return void
     *
     * @see https://developers.intercom.com/intercom-api-reference/reference#admin-initiated-conversation
     */
    public function send($notifiable, Notification $notification): void
    {
        try {
            $this->sendNotification($notifiable, $notification);
        } catch (BadResponseException $exception) {
            throw new RequestException($exception, $exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @return \Intercom\IntercomClient
     */
    public function getClient(): IntercomClient
    {
        return $this->client;
    }

    /**
     * @param mixed        $notifiable
     * @param Notification $notification
     *
     * @throws MessageIsNotCompleteException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function sendNotification($notifiable, Notification $notification): void
    {
        if (false === $notification instanceof IntercomNotification) {
            throw new InvalidArgumentException(
                sprintf('The notification must implement %s interface', IntercomNotification::class)
            );
        }

        /** @var IntercomMessage $message */
        $message = $notification->toIntercom($notifiable);
        if (false === $message->toIsGiven()) {
            if (false === $to = $notifiable->routeNotificationFor('intercom')) {
                throw new MessageIsNotCompleteException($message, 'Recipient is not provided');
            }

            $message->to($to);
        }

        if (false === $message->isValid()) {
            throw new MessageIsNotCompleteException(
                $message,
                'The message is not valid. Please check that you have filled required params'
            );
        }

        $this->client->messages->create(
            $message->toArray()
        );
    }
}
