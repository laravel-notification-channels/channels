<?php

namespace NotificationChannels\Zendesk;

use Illuminate\Support\Arr;
use Zendesk\API\HttpClient;
use Illuminate\Notifications\Notification;
use NotificationChannels\Zendesk\Exceptions\CouldNotSendNotification;

class ZendeskChannel
{
    /** @var HttpClient */
    protected $client;

    /** @var array */
    protected $parameters;

    /** @param HttpClient $client */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Zendesk\Exceptions\InvalidConfiguration
     * @throws \NotificationChannels\Zendesk\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $this->parameters = $notification->toZendesk($notifiable)->toArray();

        $this->prepareParameter($notifiable);

        try {
            $this->client->tickets()->create($this->parameters);
        } catch (\Exception $e) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($e->getMessage());
        }
    }

    /**
     * Prepare the parameters before to be send.
     *
     * @param mixed $notifiable
     */
    private function prepareParameter($notifiable)
    {
        // Check if the requester data is not set
        if (! isset($this->parameters['requester']['name']) || $this->parameters['requester']['name'] === '') {
            $routing = collect($notifiable->routeNotificationFor('Zendesk'));
            if (! Arr::has($routing, ['name', 'email'])) {
                return;
            }

            $this->parameters['requester']['name'] = $routing['name'];
            $this->parameters['requester']['email'] = $routing['email'];
        }
    }
}
