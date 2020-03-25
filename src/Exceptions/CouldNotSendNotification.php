<?php

namespace NotificationChannels\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface;

class CouldNotSendNotification extends Exception
{
    private $response;

    /**
     * @param ResponseInterface $response
     * @param string $message
     * @param int|null $code
     */
    public function __construct(ResponseInterface $response, string $message, int $code = null)
    {
        $this->response = $response;
        $this->message = $message;
        $this->code = $code ?? $response->getStatusCode();

        parent::__construct($message, $code);
    }

    /**
     * @param ResponseInterface $response
     * @return self
     */
    public static function serviceRespondedWithAnError(ResponseInterface $response)
    {
        return new self(
            $response,
            sprintf('Google Hangouts Chat API responded with an error: `%s`', $response->getBody()->getContents())
        );
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }
}
