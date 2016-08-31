<?php

namespace NotificationChannels\Cmsms\Test;

use NotificationChannels\Cmsms\CmsmsMessage;
use NotificationChannels\Cmsms\Exceptions\InvalidMessage;

class CmsmsMessageTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $message = new CmsmsMessage;

        $this->assertInstanceOf(CmsmsMessage::class, $message);
    }

    /** @test */
    public function it_can_accept_body_content_when_created()
    {
        $message = new CmsmsMessage('Foo');

        $this->assertEquals('Foo', $message->body);
    }

    /** @test */
    public function it_supports_create_method()
    {
        $message = CmsmsMessage::create('Foo');

        $this->assertInstanceOf(CmsmsMessage::class, $message);
        $this->assertEquals('Foo', $message->body);
    }

    /** @test */
    public function it_can_set_body()
    {
        $message = (new CmsmsMessage)->setBody('Bar');

        $this->assertEquals('Bar', $message->body);
    }

    /** @test */
    public function it_can_set_originator()
    {
        $message = (new CmsmsMessage)->setOriginator('APPNAME');

        $this->assertEquals('APPNAME', $message->originator);
    }

    /** @test */
    public function it_can_set_recipient_from_string()
    {
        $message = (new CmsmsMessage)->setRecipient('0031612345678');

        $this->assertEquals('0031612345678', $message->recipient);
    }

    /** @test */
    public function it_can_set_reference()
    {
        $message = (new CmsmsMessage)->setReference('REFERENCE123');

        $this->assertEquals('REFERENCE123', $message->reference);
    }

    /** @test */
    public function it_cannot_set_an_empty_reference()
    {
        $this->setExpectedException(InvalidMessage::class);

        (new CmsmsMessage)->setReference('');
    }

    /** @test */
    public function it_cannot_set_a_reference_thats_to_long()
    {
        $this->setExpectedException(InvalidMessage::class);

        (new CmsmsMessage)->setReference('UmSM7h8I1nySJm0A8IqcU3LDswO7ojfJn');
    }

    /** @test */
    public function it_cannot_set_a_reference_that_contains_non_alpha_numeric_values()
    {
        $this->setExpectedException(InvalidMessage::class);

        (new CmsmsMessage)->setReference('@#$*A*Sjks87');
    }

    /** @test */
    public function it_xml_contains_only_filled_parameters()
    {
        $message = new CmsmsMessage('Foo');

        $this->assertEquals([
            'BODY' => 'Foo',
        ], $message->toXmlArray());
    }
}
