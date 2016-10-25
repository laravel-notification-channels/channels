<?php

namespace NotificationChannels\DiscordWebhook\Test;

use NotificationChannels\DiscordWebhook\DiscordWebhookEmbedField;

class DiscordWebhookEmbedFieldTest extends \PHPUnit_Framework_TestCase
{
    /** @var \NotificationChannels\DiscordWebhook\DiscordWebhookEmbedField */
    protected $embedField;

    public function setUp()
    {
        parent::setUp();
        $this->embedField = new DiscordWebhookEmbedField('construct name', 'and value');
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /** @test */
    public function it_can_construct_with_name_and_value()
    {
        $message = new DiscordWebhookEmbedField('my construct name', 'my construct value');
        $this->assertEquals('my construct name', $message->name);
        $this->assertEquals('my construct value', $message->value);
    }

    /** @test */
    public function it_can_change_the_name()
    {
        $this->embedField->name('my name');
        $this->assertEquals('my name', $this->embedField->name);
    }

    /** @test */
    public function it_can_change_the_value()
    {
        $this->embedField->value('my value');
        $this->assertEquals('my value', $this->embedField->value);
    }

    /** @test */
    public function it_can_enable_inline()
    {
        $this->embedField->inline();
        $this->assertTrue($this->embedField->inline);
    }

    /** @test */
    public function it_can_disable_inline()
    {
        $this->embedField->inline(false);
        $this->assertFalse($this->embedField->inline);
    }
}
