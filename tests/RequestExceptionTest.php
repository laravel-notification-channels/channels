<?php

namespace FtwSoft\NotificationChannels\Intercom\Tests;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Exception\RequestException as BaseRequestException;
use FtwSoft\NotificationChannels\Intercom\Exceptions\RequestException;

class RequestExceptionTest extends TestCase
{
    public function testItReturnsBaseExceptionProvidedToConstruct(): void
    {
        $baseException = new BaseRequestException(
            'It is an exception',
            new Request('get', 'http://foo.bar')
        );
        $exception = new RequestException($baseException);
        self::assertEquals($exception->getBaseException(), $baseException);
    }
}
