<?php

namespace NotificationChannels\NetGsm\Test;

use NotificationChannels\NetGsm\NetGsmMessage;
use PHPUnit\Framework\TestCase;

class NetGsmMessageTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $message = new NetGsmMessage;

        $this->assertInstanceOf(NetGsmMessage::class, $message);
    }

    /** @test */
    public function it_can_accept_body_content_when_created()
    {
        $message = new NetGsmMessage('Foo');

        $this->assertEquals('Foo', $message->body);
    }

    /** @test */
    public function it_supports_create_method()
    {
        $message = NetGsmMessage::create('Foo');

        $this->assertInstanceOf(NetGsmMessage::class, $message);
        $this->assertEquals('Foo', $message->body);
    }

    /** @test */
    public function it_can_set_body()
    {
        $message = (new NetGsmMessage)->setBody('Bar');

        $this->assertEquals('Bar', $message->body);
    }

    /** @test */
    public function it_can_set_header()
    {
        $message = (new NetGsmMessage)->setHeader('COMPANY');

        $this->assertEquals('COMPANY', $message->header);
    }

    /** @test */
    public function it_can_set_recipients_from_array()
    {
        $message = (new NetGsmMessage)->setRecipients([31650520659, 31599858770]);

        $this->assertEquals(['31650520659', '31599858770'], $message->recipients);
    }

    /** @test */
    public function it_can_set_recipients_from_integer()
    {
        $message = (new NetGsmMessage)->setRecipients(31650520659);

        $this->assertEquals([31650520659], $message->recipients);
    }

    /** @test */
    public function it_can_set_recipients_from_string()
    {
        $message = (new NetGsmMessage)->setRecipients('31650520659');

        $this->assertEquals(['31650520659'], $message->recipients);
    }
}
