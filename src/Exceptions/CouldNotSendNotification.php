<?php

namespace NotificationChannels\PersianSms\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface; // For handling HTTP responses in errors

class CouldNotSendNotification extends Exception
{
    /**
     * Thrown when the recipient is missing or invalid.
     *
     * @param string $message
     * @return static
     */
    public static function recipientNotProvided(string $message = 'Recipient not provided or invalid.'): static
    {
        return new static($message);
    }

    /**
     * Thrown when the sender number (originator) is missing.
     *
     * @return static
     */
    public static function senderNotProvided(): static
    {
        return new static('Sender (originator/from number) was not provided in message or configuration.');
    }

    /**
     * Thrown when the message content is not provided for a normal SMS.
     *
     * @return static
     */
    public static function contentNotProvided(): static
    {
        return new static('SMS content was not provided for a normal message.');
    }

    /**
     * Thrown when a pattern code is required but not provided.
     *
     * @return static
     */
    public static function missingPatternCode(): static
    {
        return new static('Pattern code was not provided for a pattern-based message.');
    }

    /**
     * Thrown when pattern variables are invalid or not an array.
     *
     * @return static
     */
    public static function invalidPatternVariables(): static
    {
        return new static('Pattern variables are invalid or not provided as an array.');
    }

    /**
     * Thrown when the message object passed to the channel is invalid.
     *
     * @param mixed $message
     * @return static
     */
    public static function invalidMessageObject($message): static
    {
        $className = is_object($message) ? get_class($message) : 'Unknown';
        return new static("The message object provided was invalid. Expected an instance of IPPanelMessage or a string, got {$className}.");
    }

    /**
     * Thrown when the SMS service (e.g., IPPanel) responds with an error.
     *
     * @param string $errorMessage The error message from the service.
     * @param int|null $statusCode The HTTP status code.
     * @param array|null $responseBody The decoded response body.
     * @param Exception|null $previous Previous exception if any.
     * @return static
     */
    public static function serviceRespondedWithAnError(
        string $errorMessage,
        ?int $statusCode = null,
        ?array $responseBody = null,
        ?Exception $previous = null
    ): static {
        $message = "SMS service responded with an error: \"{$errorMessage}\"";
        if ($statusCode) {
            $message .= " (Status Code: {$statusCode})";
        }
        // You could add more details from $responseBody if needed, for logging purposes.
        // For example: $message .= " | Response: " . json_encode($responseBody);

        return new static($message, $statusCode ?? 0, $previous);
    }

    /**
     * Thrown for a generic error during the sending process.
     *
     * @param string $reason
     * @param Exception|null $previous
     * @return static
     */
    public static function genericError(string $reason, ?Exception $previous = null): static
    {
        return new static("Could not send SMS: {$reason}", 0, $previous);
    }

    /**
     * Thrown when the API key is missing or invalid.
     * (This might be better handled during service provider setup, but can be a runtime check too)
     *
     * @return static
     */
    public static function apiKeyNotProvided(): static
    {
        return new static('IPPanel API key is missing or not configured.');
    }

    /**
     * Thrown when the HTTP client is not available.
     *
     * @return static
     */
    public static function httpClientNotAvailable(): static
    {
        return new static('HTTP client (Guzzle) is not available or not configured correctly.');
    }
}
