<?php

namespace FtwSoft\NotificationChannels\Intercom;

use FtwSoft\NotificationChannels\Intercom\Contracts\IntercomNotification;
use FtwSoft\NotificationChannels\Intercom\Exceptions\InvalidArgumentException;
use FtwSoft\NotificationChannels\Intercom\Exceptions\MessageIsNotCompleteException;
use FtwSoft\NotificationChannels\Intercom\Exceptions\RequestException;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Notifications\Notification;
use Intercom\IntercomClient;

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
            if (!$notification instanceof IntercomNotification) {
                throw new InvalidArgumentException(
                    sprintf('The notification must implement %s interface', IntercomNotification::class)
                );
            }

            $message = $notification->toIntercom($notifiable);

            if (!$message->toIsGiven()) {
                if (!$to = $notifiable->routeNotificationFor('intercom')) {
                    throw new MessageIsNotCompleteException($message, 'Recipient is not provided');
                }

                $message->to($to);
            }

            if (!$message->isValid()) {
                throw new MessageIsNotCompleteException(
                    $message,
                    'The message is not valid. Please check that you have filled required params'
                );
            }

            $this->client->messages->create(
                $message->toArray()
            );
        } catch (BadResponseException $exception) {
            throw new RequestException($exception);
        }
    }
}
