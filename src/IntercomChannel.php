<?php

namespace FtwSoft\NotificationChannels\Intercom;

use Intercom\IntercomClient;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Notifications\Notification;
use GuzzleHttp\Exception\BadResponseException;
use FtwSoft\NotificationChannels\Intercom\Exceptions\RequestException;
use FtwSoft\NotificationChannels\Intercom\Exceptions\MessageIsNotCompleteException;

/**
 * Class IntercomNotificationChannel.
 */
class IntercomChannel
{
    /**
     * @var IntercomClient
     */
    private $client;

    /**
     * @param IntercomClient $client
     */
    public function __construct(IntercomClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification via Intercom API.
     *
     * @param mixed        $notifiable
     * @param Notification $notification
     *
     * @return void
     *
     * @throws MessageIsNotCompleteException When message is not filled correctly
     * @throws GuzzleException               Other Guzzle uncatched exceptions
     * @throws RequestException              When server responses with a bad HTTP
     *                                                                                         code
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
     * @return IntercomClient
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
     * @throws GuzzleException
     */
    protected function sendNotification($notifiable, Notification $notification): void
    {
        /** @var IntercomMessage $message */
        $message = $notification->toIntercom($notifiable);
        if (!$message->toIsGiven()) {
            if (false === $to = $notifiable->routeNotificationFor('intercom')) {
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
    }
}
