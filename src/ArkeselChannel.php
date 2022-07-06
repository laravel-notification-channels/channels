<?php

namespace NotificationChannels\ArkeselSms;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use NotificationChannels\ArkeselSms\Exceptions\CouldNotSendNotification;

class ArkeselChannel
{
    protected Client $client;
    protected string $apiKey;
    protected string $apiVersion;
    protected string $smsUrl;
    protected string $smsSender;
    protected ?string $smsCallbackUrl;
    protected bool $smsSandbox;

    public function __construct(array $config = [])
    {
        $this->client = new Client(['base_uri' => 'https://sms.arkesel.com']);
        $this->apiKey = Arr::get($config, 'api_key');
        $this->apiVersion = Arr::get($config, 'api_version', 'v2');
        $this->smsUrl = Arr::get($config, 'sms_url', 'https://sms.arkesel.com/api/v2/sms/send');
        $this->smsSender = Arr::get($config, 'sms_sender');
        $this->smsCallbackUrl = Arr::get($config, 'sms_callback_url');
        $this->smsSandbox = Arr::get($config, 'sms_sandbox', true);
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     *
     * @throws \NotificationChannels\ArkeselSms\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $msg = $notification->toArkesel($notifiable);

        if (is_string($msg)) {
            $message = new ArkeselMessage(message: $msg);
        }

        if ($msg instanceof ArkeselMessage) {
            $message = $msg;
        }

        if (! empty($message->recipients)) {
            $recipients = is_string($message->recipients) ? explode(',', $message->recipients) : $message->recipients;
        } else {
            $recipients = Arr::wrap($notifiable->routeNotificationFor('arkesel', $notification));
        }

        if (empty($recipients)) {
            throw new Exception('No recipients were specified for this notification');
        }

        $payload = $this->apiVersion === 'v1'
            ? array_filter([
                'action' => 'send-sms',
                'api_key' => $message->apiKey ?? $this->apiKey,
                'to' => implode(',', $recipients),
                'from' => $message->sender ?? $this->smsSender,
                'sms' => $message->message,
                'schedule' => $message->schedule ?? null,  // dd-mm-yyyy hh:mm AM/PM
            ])
            : array_filter([
                'sender' => $message->sender ?? $this->smsSender,
                'recipients' => $recipients,
                'message' => $message->message,
                'callback_url' => $message->callbackUrl ?? $this->smsCallbackUrl,
                'scheduled_date' => $message->schedule ?? null,  // 'Y-m-d H:i A' //E.g: "2021-03-17 07:00 AM"
                'sandbox' => $message->sandbox ?? $this->smsSandbox,
            ]);


        try {
            $response = $this->client->request(
                method: $this->apiVersion === 'v1' ? 'GET' : 'POST',
                uri: $this->smsUrl,
                options: array_filter([
                    'headers' => array_filter([
                        'api-key' => $this->apiVersion === 'v2' ? $this->apiKey : null,
                    ]),
                    'query' => $this->apiVersion === 'v1' ? $payload : null,
                    'json' => $payload,
                ]),
            );
        } catch (\Throwable $th) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
        }
    }
}
