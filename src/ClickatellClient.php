<?php

namespace NotificationChannels\Clickatell;

use Clickatell\Api\ClickatellHttp;
use NotificationChannels\Clickatell\Exceptions\CouldNotSendNotification;

class ClickatellClient
{
    const SUCCESSFUL_SEND = 0;
    const AUTH_FAILED = 1;
    const INVALID_DEST_ADDRESS = 105;
    const INVALID_API_ID = 108;
    const CANNOT_ROUTE_MESSAGE = 114;
    const DEST_MOBILE_BLOCKED = 121;
    const DEST_MOBILE_OPTED_OUT = 122;
    const MAX_MT_EXCEEDED = 130;
    const NO_CREDIT_LEFT = 301;
    const INTERNAL_ERROR = 901;

    /**
     * @param string $user
     * @param string $pass
     * @param string $apiId
     */
    public function __construct($user, $pass, $apiId)
    {
        $this->clickatell = new ClickatellHttp($user, $pass, $apiId);
    }

    /**
     * @param string|array $to String or Array of numbers
     * @param string $message
     * @return void
     */
    public function send($to, $message)
    {
        $to = (is_string($to)) ? [$to] : $to;

        $response = $this->clickatell->sendMessage($to, $message);

        $this->handleProviderResponses($response);
    }

    /**
     * @return array
     */
    public function getFailedQueueCodes()
    {
        return [
            self::AUTH_FAILED,
            self::INVALID_DEST_ADDRESS,
            self::INVALID_API_ID,
            self::CANNOT_ROUTE_MESSAGE,
            self::DEST_MOBILE_BLOCKED,
            self::DEST_MOBILE_OPTED_OUT,
            self::MAX_MT_EXCEEDED,
        ];
    }

    /**
     * @return array
     */
    public function getRetryQueueCodes()
    {
        return [
            self::NO_CREDIT_LEFT,
            self::INTERNAL_ERROR,
        ];
    }

    /**
     * @param array $responses
     * @throws CouldNotSendNotification
     */
    protected function handleProviderResponses(array $responses)
    {
        collect($responses)->each(function ($response) {
            $errorCode = (int)$response->errorCode;
            switch ($errorCode) {
                default:
                    throw CouldNotSendNotification::serviceRespondedWithAnError(
                        (string)$response->error,
                        $errorCode
                    );
                    break;
                case self::SUCCESSFUL_SEND:
                    // Do nothing, message has finished successfully :)
                    break;
            }
        });
    }
}
