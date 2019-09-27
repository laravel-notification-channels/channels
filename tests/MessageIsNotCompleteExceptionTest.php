<?php

namespace FtwSoft\NotificationChannels\Intercom\Tests;

use PHPUnit\Framework\TestCase;
use FtwSoft\NotificationChannels\Intercom\IntercomMessage;
use FtwSoft\NotificationChannels\Intercom\Exceptions\MessageIsNotCompleteException;

class MessageIsNotCompleteExceptionTest extends TestCase
{
    public function testItReturnsMessageProvidedToConstruct(): void
    {
        $message = IntercomMessage::create('TEST');
        $exception = new MessageIsNotCompleteException($message);
        self::assertEquals($message, $exception->getIntercomMessage());
    }
}
