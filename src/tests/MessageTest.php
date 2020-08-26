<?php

namespace NotificationChannels\Signal\Test;

use NotificationChannels\Signal\SignalMessage;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
  // @var NotificationChannels\Signal\SignalMessage
  protected $message;

  // @test
  protected function setUp()
  {
    parent::setUp();

    $this->message = new SignalMessage();
  }

  public function provides_a_create_method()
  {
    $message = SignalMessage::create();

    $this->assertInstanceOf(SignalMessage::class, $message);
  }

  public function sets_username()
  {
    $this->message->username('+12345556789');

    $this->assertEquals('+12345556789', $this->message->username);
  }

  public function sets_recipient()
  {
    $this->message->recipient('+12345556789');

    $this->assertEquals('+12345556789', $this->message->recipient);
  }

  public function sets_message()
  {
    $this->message('Test message');

    $this->assertEquals('Test message', $this->message);
  }

}
