<?php

namespace NotificationChannels\Notify\Test;

use Orchestra\Testbench\TestCase;
use NotificationChannels\Notify\NotifyMessage;

class NotifyMessageTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $message = new NotifyMessage;

        $this->assertInstanceOf(NotifyMessage::class, $message);
    }

    /** @test */
    public function it_supports_create_method()
    {
        $message = NotifyMessage::create();

        $this->assertInstanceOf(NotifyMessage::class, $message);
    }

    /** @test */
    public function it_can_set_clientid()
    {
        $message = (new NotifyMessage)->setClientId('123');

        $this->assertEquals('123', $message->getClientId());
    }

    /** @test */
    public function it_can_set_secret()
    {
        $message = (new NotifyMessage)->setSecret('123');

        $this->assertEquals('123', $message->getSecret());
    }

    /** @test */
    public function it_can_set_params_from_array()
    {
        $message = (new NotifyMessage)->setParams(['test' => 'test']);

        $this->assertEquals(['test' => 'test'], $message->getParams());
    }

    /** @test */
    public function it_can_set_notification_type()
    {
        $message = (new NotifyMessage)->setNotificationType('test');

        $this->assertEquals('test', $message->getNotificationType());
    }

    /** @test */
    public function it_can_set_language()
    {
        $message = (new NotifyMessage)->setLanguage('nl');

        $this->assertEquals('nl', $message->getLanguage());
    }

    /** @test */
    public function it_can_set_transport()
    {
        $message = (new NotifyMessage)->setTransport('mail');

        $this->assertEquals('mail', $message->getTransport());
    }

    /** @test */
    public function it_can_set_cc_from_array()
    {
        $message = (new NotifyMessage)->setCc([['test' => 'test']]);

        $this->assertEquals([['test' => 'test']], $message->getCc());
    }

    /** @test */
    public function it_can_set_bcc_from_array()
    {
        $message = (new NotifyMessage)->setBcc([['test' => 'test']]);

        $this->assertEquals([['test' => 'test']], $message->getBcc());
    }

    /** @test */
    public function it_can_add_recipient()
    {
        $message = (new NotifyMessage)->addRecipient('John Doe', 'john@doe.com');

        $this->assertEquals([['name' => 'John Doe', 'recipient'=>'john@doe.com']], $message->getTo());
    }
}
