<?php

namespace NotificationChannels\SMSGatewayMe;

use NotificationChannels\SMSGatewayMe\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;
use GuzzleHttp\Client;
use Carbon\Carbon;

class SMSGatewayMeChannel
{
    protected $client;

    protected $email;

    protected $password;

    protected $device_id;

    public function __construct(Client $client, $email, $password, $device_id)
    {
        $this->client = $client;
        $this->email = $email;
        $this->password = $password;
        $this->device_id = $device_id;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\SMSGatewayMe\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('sms-gateway-me')) {
            return;
        }

        $message = $notification->toSmsGatewayMe($notifiable);

        $result = $this->client->request('POST', '/api/v3/messages/send', [
          'form_params' => [
            'email' => $this->email,
            'password' => $this->password,
            'device' =>$this->device_id,
            'number' => $to,
            'message' => $message->text,
            'send_at' => Carbon::now()->timestamp,
            'expires_at' => Carbon::now()->addDay()->timestamp
          ]
        ]);

        $response = json_decode($result->getBody());

        if (count($response->result->fails)) { // replace this by the code need to check for errors
          throw CouldNotSendNotification::serviceRespondedWithAnError($response);
        }
    }
}
