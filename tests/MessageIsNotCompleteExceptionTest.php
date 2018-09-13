<?php
/**
 * @link      http://horoshop.ua
 * @copyright Copyright (c) 2015-2018 Horoshop TM
 * @author    Andrey Telesh <andrey@horoshop.ua>
 */

namespace FtwSoft\NotificationChannels\Intercom\Tests;


use FtwSoft\NotificationChannels\Intercom\Exceptions\MessageIsNotCompleteException;
use FtwSoft\NotificationChannels\Intercom\IntercomMessage;
use PHPUnit\Framework\TestCase;

class MessageIsNotCompleteExceptionTest extends TestCase
{

    public function testItReturnsMessageProvidedToConstruct(): void
    {
        $message = IntercomMessage::create('TEST');
        $exception = new MessageIsNotCompleteException($message);
        $this->assertEquals($message, $exception->getIntercomMessage());
    }

}