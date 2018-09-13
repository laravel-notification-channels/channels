<?php

namespace FtwSoft\NotificationChannels\Intercom\Tests;

use FtwSoft\NotificationChannels\Intercom\Exceptions\RequestException;
use GuzzleHttp\Exception\RequestException as BaseRequestException;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

class RequestExceptionTest extends TestCase
{
    public function testItReturnsBaseExceptionProvidedToConstruct(): void
    {
        $baseException = new BaseRequestException(
            'It is an exception',
            new Request('get', 'http://foo.bar')
        );
        $exception = new RequestException($baseException);
        $this->assertEquals($exception->getBaseException(), $baseException);
    }
}
