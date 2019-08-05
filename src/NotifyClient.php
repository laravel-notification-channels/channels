<?php

namespace NotificationChannels\Notify;

use Exception;
use GuzzleHttp\Client;
use NotificationChannels\Notify\Exceptions\InvalidConfiguration;
use NotificationChannels\Notify\Exceptions\InvalidMessageObject;
use NotificationChannels\Notify\Exceptions\CouldNotSendNotification;

class NotifyClient
{
    /**
     * @var string
     */
    const API_ENDPOINT = 'https://api.notify.eu/notification/send';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var endPointUrl
     */
    private $endpointUrl;

    /**
     * NotifyClient constructor.
     * @param Client $client
     * @param Config $config
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param NotifyMessage $message
     */
    public function validateMessage(NotifyMessage $message)
    {
        if (empty($message->getClientId()) or empty($message->getSecret())) {
            throw InvalidConfiguration::configurationNotSet();
        }
        if (empty($message->getNotificationType())) {
            throw InvalidMessageObject::missingNotificationType();
        }
        if (empty($message->getTransport())) {
            throw InvalidMessageObject::missingTransport();
        }
        if (empty($message->getTo())) {
            throw InvalidMessageObject::missingRecipients();
        }
    }

    /**
     * Send the Message.
     * @param NotifyMessage $message
     * @throws CouldNotSendNotification
     */
    public function send(NotifyMessage $message)
    {
        $message->setClientId(config('services.notify.clientID'));
        $message->setSecret(config('services.notify.secret'));
        if (empty($message->getTransport())) {
            $message->setTransport(config('services.notify.transport'));
        }

        $this->validateMessage($message);
        $this->endpointUrl = config('services.notify.url') ? config('services.notify.url') : self::API_ENDPOINT;

        try {
            $response = $this->client->request('POST', $this->endpointUrl, [
                'body' => json_encode(new NotifyMessageResource($message)),
            ]);

            return $response;
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithNotify($exception);
        }
    }
}
