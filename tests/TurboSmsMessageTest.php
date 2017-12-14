<?php

namespace NotificationChannels\TurboSms\Test;

use Mockery;
use PHPUnit\Framework\TestCase;
use NotificationChannels\TurboSms\TurboSmsClient;
use NotificationChannels\TurboSms\TurboSmsMessage;

class TurboSmsMessageTest extends TestCase
{
    private $httpClient;

    private $turboSmsMessage;

    private const SOME_CONTENT = 'This is some content';
    private const SOME_SENDER = 'Sender';

    public function setUp()
    {
        parent::setUp();

        $this->httpClient = Mockery::mock( TurboSmsClient::class );
        $this->turboSmsMessage = new TurboSmsMessage( self::SOME_CONTENT );
    }

    /** @test */
    public function it_sets_a_TurboSms_message()
    {
        $this->assertInstanceOf( TurboSmsMessage::class, $this->turboSmsMessage );
    }

    /** @test */
    public function it_can_construct_with_a_new_message()
    {
        $actual = TurboSmsMessage::create( self::SOME_CONTENT );

        $this->assertEquals( self::SOME_CONTENT, $actual->getContent() );
    }

    /** @test */
    public function it_can_set_new_content()
    {
        $actual = TurboSmsMessage::create();

        $this->assertEmpty( $actual->getContent() );

        $actual->content( self::SOME_CONTENT );

        $this->assertEquals( self::SOME_CONTENT, $actual->getContent() );
    }

    /** @test */
    public function it_can_set_new_sender()
    {
        $actual = TurboSmsMessage::create();

        $actual->sender( self::SOME_SENDER );

        $this->assertEquals( self::SOME_SENDER, $actual->getSender() );
    }

    /** @test */
    public function it_can_construct_with_a_new_message_and_sender()
    {
        $actual = TurboSmsMessage::create( self::SOME_CONTENT, self::SOME_SENDER );

        $this->assertEquals( self::SOME_CONTENT, $actual->getContent() );
        $this->assertEquals( self::SOME_SENDER, $actual->getSender() );
    }
}
