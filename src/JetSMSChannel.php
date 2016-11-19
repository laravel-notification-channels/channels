<?php
/**
 * Author: Hilmi Erdem KEREN
 * Date: 17/11/2016.
 */
namespace NotificationChannels\JetSMS;

use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Events\Dispatcher;
use NotificationChannels\JetSMS\Events\MessageWasSent;
use NotificationChannels\JetSMS\Events\SendingMessage;
use NotificationChannels\JetSMS\Clients\JetSMSClientInterface;
use NotificationChannels\JetSMS\Clients\JetSMSApiResponseInterface;
use NotificationChannels\JetSMS\Exceptions\CouldNotSendNotification;

/**
 * Class JetSMSChannel.
 */
final class JetSMSChannel
{
    /**
     * The guzzle http client.
     *
     * @var JetSMSClientInterface
     */
    private $client;

    /**
     * The Laravel event dispatcher implementation.
     *
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * JetSMSChannel constructor.
     *
     * @param JetSMSClientInterface $client
     * @param Dispatcher            $dispatcher
     */
    public function __construct(JetSMSClientInterface $client, Dispatcher $dispatcher)
    {
        $this->client = $client;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed                                  $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @throws \NotificationChannels\JetSMS\Exceptions\CouldNotSendNotification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $to = $notifiable->routeNotificationFor('JetSMS');

        if (empty($to)) {
            throw CouldNotSendNotification::missingRecipient();
        }

        $message = $notification->toJetSMS($notifiable);

        if (is_string($message)) {
            $message = new JetSMSMessage($message, $to);
        }

        if (strlen($message->content()) > 180) {
            throw CouldNotSendNotification::contentLengthLimitExceeded();
        }

        $this->client->addToRequest($message);
        $this->fireSendingEvent($message);
        $response = $this->sendMessage();
        $this->fireSentEvent($message, $response);
    }

    /**
     * Send the message.
     *
     * @return JetSMSApiResponseInterface
     */
    private function sendMessage()
    {
        $response = $this->client->sendRequest();

        return $response;
    }

    /**
     * Fire the sending event for the prepared message.
     *
     * @param JetSMSMessageInterface $message
     */
    private function fireSendingEvent(JetSMSMessageInterface $message)
    {
        $this->dispatcher->fire(new SendingMessage($message));
    }

    /**
     * Fire  the sent event for the message.
     *
     * @param JetSMSMessageInterface      $message
     * @param  JetSMSApiResponseInterface $response
     */
    private function fireSentEvent($message, JetSMSApiResponseInterface $response)
    {
        $this->dispatcher->fire(new MessageWasSent($message, $response));
    }
}
