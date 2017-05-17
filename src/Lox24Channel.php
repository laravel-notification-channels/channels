<?php

namespace NotificationChannels\Lox24;

use NotificationChannels\Lox24\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;

class Lox24Channel
{

    /**
     * @var Lox24
     */
    protected $lox24;

    public function __construct(Lox24 $lox24)
    {
        $this->lox24 = $lox24;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toLox24')) {
            throw new CouldNotSendNotification('You must implement the method "toLox24()" in your notification object');
        }

        $message = $notification->toLox24($notifiable);
        if (is_string($message)) {
            $message = Lox24Message::create($message);
        }

        // No receiver given?
        if (!$message->toExists()) {
            if (!$to = $notifiable->routeNotificationFor('lox24')) {
                throw new CouldNotSendNotification('Receiver Phone Number not given');
            }
            $message->setTo($to);
        }

        $params = $message->toArray();

        $response = $this->lox24->sendMessage($params);

        // SimpleXML Error Handling
        $use_errors = libxml_use_internal_errors(true);
        $responseBody = simplexml_load_string((string)$response->getBody());
        if (false === $responseBody) {
            throw CouldNotSendNotification::lox24RespondedWithAnError(new \Exception('Could not parse response XML'));
        }
        libxml_clear_errors();
        libxml_use_internal_errors($use_errors);

        // Feedback Error Handling
        // 1xx => success
        // 2xx => usererror
        // 3xx => lox24 error

        switch((string)$responseBody->code) {
            case '100' :
            case '101' :
            case '102' :
                //success
                break;
            default:
                throw CouldNotSendNotification::lox24RespondedWithAnError(new \Exception((string)$responseBody->codetext));
        }

    }
}
