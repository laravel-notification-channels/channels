<?php

namespace NotificationChannels\Vodafone;

use NotificationChannels\Vodafone\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;

class VodafoneChannel
{

    /**
     * @var string Vodafone's API endpoint
     */
    protected $endpoint = 'https://www.smsertech.com/apisend';


    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Vodafone\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {

        $message = $notification->toVodafone($notifiable, $notification);

        $fields = [
            'username' => urlencode(config('services.vodafone.username')),
            'password' => urlencode(config('services.vodafone.password')),
            'to' => urlencode($notifiable->routeNotificationFor('vodafone')),
            'message' => urlencode($message->content),
            'from' => urlencode($message->from),
            'format' => urlencode('json'),
            'flash' => urlencode(0)
        ];

        $fields_string = '';
        foreach($fields as $key => $value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_URL, $this->endpoint);
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result)[0];

        if ($response->status === "ERROR") { // replace this by the code need to check for errors
            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
        }
    }
}
