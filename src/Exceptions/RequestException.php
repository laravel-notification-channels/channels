<?php
/**
 * @link      http://horoshop.ua
 * @copyright Copyright (c) 2015-2018 Horoshop TM
 * @author    Andrey Telesh <andrey@horoshop.ua>
 */

namespace FtwSoft\NotificationChannels\Intercom\Exceptions;

use Throwable;
use GuzzleHttp\Exception\RequestException as BaseRequestException;

class RequestException extends IntercomException
{

    /**
     * @var BaseRequestException
     */
    private $baseException;

    /**
     * @inheritDoc
     */
    public function __construct(
        BaseRequestException $baseException,
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $this->baseException = $baseException;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return BaseRequestException
     */
    public function getBaseException(): BaseRequestException
    {
        return $this->baseException;
    }

}