<?php

namespace NotificationChannels\Notify;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use NotificationChannels\Notify\Exceptions\CouldNotSendNotification;
use NotificationChannels\Notify\Exceptions\InvalidConfiguration;
use NotificationChannels\Notify\Exceptions\InvalidMessageObject;

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
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    private $endpointUrl;

    /**
     * NotifyClient constructor.
     * @param Client $client
     * @param array $config
     */
    public function __construct(Client $client, array $config)
    {
        $this->client = $client;
        $this->config = $config;
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
            throw InvalidMessageObject::missingRecipient();
        }
    }

    /**
     * Send the Message.
     * @param NotifyMessage $message
     * @throws CouldNotSendNotification
     */
    public function send(NotifyMessage $message)
    {
        $message->setClientId($this->config['clientID']);
        $message->setSecret($this->config['secret']);
        if (empty($message->getTransport())) {
            $message->setTransport($this->config['transport']);
        }

        $this->validateMessage($message);
        $this->endpointUrl = isset($this->config['url']) ? $this->config['url'] : self::API_ENDPOINT;

        try {
            $response = $this->client->request('POST', $this->endpointUrl, [
                'body' => json_encode($message),
            ]);

            return $response;
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithNotify($exception);
        }
    }
}
