<?php

namespace Zilmoney\OnlineCheckWriter\Exceptions;

use Exception;

class OnlineCheckWriterException extends Exception
{
    protected ?array $response = null;

    /**
     * Create a new exception with API response data.
     */
    public static function withResponse(string $message, array $response, int $code = 0): static
    {
        $exception = new static($message, $code);
        $exception->response = $response;
        return $exception;
    }

    /**
     * Get the API response data if available.
     */
    public function getResponse(): ?array
    {
        return $this->response;
    }

    /**
     * Check if this is a validation error.
     */
    public function isValidationError(): bool
    {
        return $this->getCode() === 422;
    }

    /**
     * Check if this is an authentication error.
     */
    public function isAuthenticationError(): bool
    {
        return $this->getCode() === 401;
    }

    /**
     * Check if this is a rate limit error.
     */
    public function isRateLimitError(): bool
    {
        return $this->getCode() === 429;
    }
}
