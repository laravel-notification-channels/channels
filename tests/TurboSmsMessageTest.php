<?php

namespace NotificationChannels\TurboSms\Test;

use NotificationChannels\TurboSms\TurboSmsMessage;

class TurboSmsMessageTest extends \PHPUnit_Framework_TestCase
{
    protected $TurboSmsMessage;
    private $httpClient;

    public function setUp()
    {
        parent::setUp();
        $this->httpClient = \Mockery::mock(TurboSmsHttp::class);
        $this->TurboSmsMessage = new TurboSmsMessage($this->httpClient);
    }

    /** @test */
    public function it_sets_a_TurboSms_message()
    {
        $this->assertInstanceOf(
            TurboSmsMessage::class,
            $this->TurboSmsMessage
        );
    }

    /** @test */
    public function it_can_construct_with_a_new_message()
    {
        $actual = TurboSmsMessage::create('This is some content');

        $this->assertEquals('This is some content', $actual->getContent());
    }

    /** @test */
    public function it_can_set_new_content()
    {
        $actual = TurboSmsMessage::create();

        $this->assertEmpty($actual->getContent());

        $actual->content('Hello');

        $this->assertEquals('Hello', $actual->getContent());
    }
}
