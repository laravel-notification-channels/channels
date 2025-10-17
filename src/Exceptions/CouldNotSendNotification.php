<?php

namespace NotificationChannels\NextcloudTalk\Exceptions;

use GuzzleHttp\Exception\ClientException;

class CouldNotSendNotification extends \Exception
{
    public static function messageNotCorrectInstance(): self
    {
        return new static('toNextcloudTalk() must return instance of NotificationChannels\NextcloudTalk\NextcloudTalkMessage');
    }

    public static function missingTo(): self
    {
        return new static('Missing channel or user to send notification');
    }

    public static function nextcloudApiError(ClientException $exception): self
    {
        $message = $exception->getResponse()->getBody();
        $code = $exception->getResponse()->getStatusCode();

        return new static("Nextcloud api error `{$code} - {$message}`");
    }
}
