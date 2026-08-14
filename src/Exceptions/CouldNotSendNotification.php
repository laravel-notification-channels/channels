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
        // The gateway authenticates via query parameter, so the request URL (with
        // credentials) is technically already gone by the time we only have a
        // response body. This redaction is a defensive backstop in case a proxy,
        // WAF, or gateway error page echoes the requested URL back in its body.
        return new self('OneWaySMS returned an unexpected response body: "'.static::redactCredentials($body).'".');
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
        // OneWaySMS authenticates via query parameter (apiusername/apipassword),
        // and Guzzle transport exceptions embed the full request URI in their
        // message. Redact here, at the single choke point every transport
        // failure passes through, so every consumer of this exception -
        // including future call sites we haven't written yet - is protected.
        //
        // Deliberately NOT passed as the previous exception below: Laravel's
        // exception handler and Monolog's formatters both recurse into
        // getPrevious()->getMessage() when logging/reporting an exception,
        // unconditionally. Attaching the raw Guzzle exception there would put
        // the unredacted, credential-bearing message right back within reach
        // of report($e) - the exact call this package's README recommends -
        // defeating the redaction above. The original exception's class name
        // is folded into the outer message instead, so the failure type is
        // still diagnosable without resurrecting the leak.
        $reason = static::redactCredentials($exception->getMessage());

        return new self(
            sprintf('The communication with OneWaySMS failed (%s). Reason: %s', $exception::class, $reason),
            (int) $exception->getCode(),
        );
    }

    /**
     * Redact OneWaySMS gateway credentials (apiusername/apipassword) from a
     * string that may contain the request URL, without disturbing anything
     * else in the string. Parameter order and URL-encoded characters in the
     * value are both handled, since the value is matched up to the next
     * delimiter rather than assumed to be plain text.
     */
    protected static function redactCredentials(string $text): string
    {
        return preg_replace(
            '/(apiusername|apipassword)=[^&\s"\'`]+/i',
            '$1=***REDACTED***',
            $text,
        ) ?? $text;
    }
}
