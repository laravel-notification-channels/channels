<?php

namespace NotificationChannels\Workplace;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Notifications\Notification;
use NotificationChannels\Workplace\Exceptions\CouldNotSendNotification;

class WorkplaceChannel
{
    /** @var ClientInterface Http client. */
    protected $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Workplace\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('workplace', $notification)) {
            throw CouldNotSendNotification::endpointNotProvided();
        }

        $message = $notification->toWorkplace($notifiable);

        $body = [
            'message' => $message->getContent(),
            'formatting' => ($message->isMarkdown() ? 'MARKDOWN' : null),
        ];

        try {
            return $this->httpClient->post(
                $to,
                ['json' => $body]
            );
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::workplaceRespondedWithAnError($exception);
        } catch (\Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithWorkplace($exception);
        }
    }
}
