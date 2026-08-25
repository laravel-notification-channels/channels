<?php

declare(strict_types=1);

namespace NotificationChannels\RuStore\Exceptions;

use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Throwable;

class RuStorePushException extends Exception
{
    /**
     * @param  string  $message
     * @param  int  $code
     * @param  Throwable|null  $previous
     */
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param  PromiseInterface|Response  $response
     * @return self
     */
    public static function fromResponse(PromiseInterface|Response $response): self
    {
        $type = match (true) {
            $response->redirect() => 'RuStoreRedirect',
            $response->clientError() => 'RuStoreClientError',
            $response->serverError() => 'RuStoreServerError',
        };

        return new self(
            message: "$type: ".$response->getBody()->getContents(),
            code: $response->getStatusCode(),
        );
    }
}
