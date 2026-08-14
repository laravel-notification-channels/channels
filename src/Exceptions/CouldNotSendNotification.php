<?php

namespace NotificationChannels\OneWaySms\Exceptions;

use Exception;
use Throwable;

class CouldNotSendNotification extends Exception
{
    /**
     * The raw error code returned by the gateway, or null when the failure was
     * raised locally before any request was made.
     */
    protected ?int $gatewayCode = null;

    public function gatewayCode(): ?int
    {
        return $this->gatewayCode;
    }

    protected static function withGatewayCode(string $message, int $code): self
    {
        $exception = new self($message, abs($code));
        $exception->gatewayCode = $code;

        return $exception;
    }

    public static function invalidCredentials(): self
    {
        return static::withGatewayCode('OneWaySMS rejected the API username or password.', -100);
    }

    public static function invalidSenderId(): self
    {
        return static::withGatewayCode('OneWaySMS rejected the sender ID. It may contain at most 11 alphanumeric characters.', -200);
    }

    public static function invalidRecipient(): self
    {
        return static::withGatewayCode('OneWaySMS rejected the recipient number. Numbers must include the country code, for example 60121234567.', -300);
    }

    public static function invalidLanguageType(): self
    {
        return static::withGatewayCode('OneWaySMS rejected the language type. It must be 1 (text) or 2 (unicode).', -400);
    }

    public static function invalidMessageCharacters(): self
    {
        return static::withGatewayCode('OneWaySMS rejected the message because it contains invalid characters.', -500);
    }

    public static function insufficientCredit(): self
    {
        return static::withGatewayCode('OneWaySMS rejected the request because the account has insufficient credit.', -600);
    }

    public static function unknownErrorCode(int $code): self
    {
        return static::withGatewayCode("OneWaySMS responded with an undocumented error code: {$code}.", $code);
    }

    public static function unexpectedResponse(string $body): self
    {
        return new self("OneWaySMS returned an unexpected response body: \"{$body}\".");
    }

    public static function tooManyRecipients(int $count, int $max): self
    {
        return new self("OneWaySMS accepts at most {$max} recipients per request, {$count} given.");
    }

    public static function emptyMessage(): self
    {
        return new self('Notification was not sent because the message content is empty.');
    }

    public static function missingSender(): self
    {
        return new self('No sender ID was given. Set services.onewaysms.sender, or call sender() on the message.');
    }

    public static function couldNotCommunicateWithOneWaySms(Throwable $exception): self
    {
        return new self(
            "The communication with OneWaySMS failed. Reason: {$exception->getMessage()}",
            (int) $exception->getCode(),
            $exception,
        );
    }
}
