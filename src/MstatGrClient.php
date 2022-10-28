<?php

namespace NotificationChannels\MstatGr;

use Illuminate\Support\Facades\Http;
use NotificationChannels\MstatGr\Exceptions\AuthKeyIsInvalid;
use NotificationChannels\MstatGr\Exceptions\AuthKeyIsMissing;
use NotificationChannels\MstatGr\Exceptions\InsufficientBalance;
use NotificationChannels\MstatGr\Exceptions\InvalidParameter;
use NotificationChannels\MstatGr\Exceptions\IpAddressInvalid;

class MstatGrClient
{
    public static $endpoint = 'https://corpsms-api.m-stat.gr/http/sms.php';

    public function send($notifiable, $notificationData)
    {
        $params = [
            'from' => $notificationData->from,
            'to' => $notificationData->to,
            'message' => $notificationData->content,
        ];

        if (! $params['to']) {
            $params['to'] = $notifiable->routeNotificationFor('MstatGr');
        }

        $response = $this->handleCall($params);

        $responseBody = $response->body();
        if (str($responseBody)->contains('ERROR: Auth Key is empty.')) {
            throw new AuthKeyIsMissing();
        }

        if (str($responseBody)->contains('ERROR: Invalid Auth Key.')) {
            throw new AuthKeyIsInvalid();
        }

        if (str($responseBody)->contains('ERROR: Invalid IP access.')) {
            throw new IpAddressInvalid();
        }

        if (str($responseBody)->contains('ERROR: Invalid IP access.')) {
            throw new IpAddressInvalid();
        }

        if (str($responseBody)->contains('ERROR: id is null.')) {
            throw new InvalidParameter('ID is null');
        }

        if (str($responseBody)->contains('ERROR: to is null.')) {
            throw new InvalidParameter('To is null');
        }

        if (str($responseBody)->contains('ERROR: text is null.')) {
            throw new InvalidParameter('Text is null');
        }

        if (str($responseBody)->contains('ERROR: from is null.')) {
            throw new InvalidParameter('From is null');
        }

        if (str($responseBody)->contains('ERROR: insufficient balance.')) {
            throw new InsufficientBalance();
        }

        if (! $response->ok()) {
            throw new \Exception('An error occurred.');
        }

        $result = explode(';', $responseBody);

        return [
            'is_successful' => $result[0] === 'OK',
            'credits' => $result[2],
        ];
    }

    public function handleCall(array $params)
    {
        return Http::asForm()->post(self::$endpoint, [
            'auth_key' => \Config::get('services.mstat.auth_key'),
            'id' => (int) now()->getPreciseTimestamp(),
            'to' => $params['to'],
            'text' => $params['message'],
            'from' => $params['from'],
        ]);
    }
}
