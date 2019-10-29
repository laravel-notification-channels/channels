<?php

namespace NotificationChannels\AllMySms\Test;

use Illuminate\Support\Arr;
use PHPUnit\Framework\TestCase;
use NotificationChannels\AllMySms\AllMySmsMessage;

class MessageTest extends TestCase
{
    /** @var \NotificationChannels\AllMySms\AllMySmsMessage */
    protected $message;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->message = new AllMySmsMessage;
    }

    /** @test */
    public function it_accepts_a_content_when_constructing_a_message()
    {
        $message = new AllMySmsMessage('A message');

        $this->assertEquals('A message', Arr::get($message->toArray(), 'message'));
    }

    /** @test */
    public function it_can_set_the_content()
    {
        $this->message->content('Hello world');

        $this->assertEquals('Hello world', Arr::get($this->message->toArray(), 'message'));
    }

    /** @test */
    public function it_can_set_the_sender()
    {
        $this->message->sender('MyCompany');

        $this->assertEquals('MyCompany', Arr::get($this->message->toArray(), 'sender'));
    }

    /** @test */
    public function it_can_set_the_campaign()
    {
        $this->message->campaign('A campaign');

        $this->assertEquals('A campaign', Arr::get($this->message->toArray(), 'campaign'));
    }

    /** @test */
    public function it_can_set_the_date()
    {
        $date = new \DateTime('now');

        $this->message->sendAt($date);

        $this->assertEquals($date->format(AllMySmsMessage::DATE_FORMAT), Arr::get($this->message->toArray(), 'date'));
    }

    /** @test */
    public function it_can_set_the_parameters()
    {
        $this->message->parameters(['value']);

        $this->assertEquals(['value'], Arr::get($this->message->toArray(), 'parameters'));
    }
}
