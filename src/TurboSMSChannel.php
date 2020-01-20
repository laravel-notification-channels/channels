<?php

namespace NotificationChannels\TurboSMS;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use NotificationChannels\TurboSMS\Exceptions\CouldNotSendNotification;

class TurboSMSChannel
{
    /**
     * Login to API endpoint.
     *
     * @var string
     */
    protected $login;

    /**
     * Password to API endpoint.
     *
     * @var string
     */
    protected $password;

    /**
     * API endpoint wsdl url.
     *
     * @var string
     */
    protected $wsdlEndpoint;

    /**
     * Registered sender. Should be requested in TurboSMS user's page.
     *
     * @var string
     */
    protected $sender;

    /**
     * Debug flag. If true, messages send/result wil be stored in Laravel log.
     *
     * @var bool
     */
    protected $debug;

    /**
     * Sandbox mode flag. If true, endpoint API will not be invoked, useful for dev purposes.
     *
     * @var bool
     */
    protected $sandboxMode;

    /**
     * @return mixed
     */
    public function getWsdlEndpoint()
    {
        return $this->wsdlEndpoint;
    }

    public function __construct(array $config = [])
    {
        $this->login = Arr::get($config, 'login');
        $this->password = Arr::get($config, 'password');
        $this->wsdlEndpoint = Arr::get($config, 'wsdlEndpoint');
        $this->sender = Arr::get($config, 'sender');
        $this->debug = Arr::get($config, 'debug', false);
        $this->sandboxMode = Arr::get($config, 'sandboxMode', false);
    }

    /**
     * @return \SoapClient
     * @throws CouldNotSendNotification
     */
    protected function getClient()
    {
        try {
            return new \SoapClient($this->wsdlEndpoint);
        } catch (\Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithEndPoint($exception);
        }
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     *
     * @param Notification $notification
     * @return void|array
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        /** @var TurboSMSMessage $message */
        $message = $notification->toTurboSMS($notifiable);
        if (is_string($message)) {
            $message = new TurboSMSMessage($message);
        }

        $sms = [
            'sender' => $this->sender,
            'destination' => $notifiable->routeNotificationFor('turbosms'),
            'text' => $message->body,
        ];

        if ($this->debug) {
            Log::info('TurboSMS sending sms - '.print_r($sms, true));
        }

        $auth = [
            'login' => $this->login,
            'password' => $this->password,
        ];

        if ($this->sandboxMode) {
            return;
        }

        $client = $this->getClient();
        $client->Auth($auth);
        $result = $client->SendSMS($sms);

        if ($this->debug) {
            Log::info('TurboSMS send result - '.print_r($result->SendSMSResult->ResultArray, true));
        }

        return $result;
    }
}
