<?php

namespace NotificationChannels\Zendesk\Test;

use Illuminate\Support\Arr;
use NotificationChannels\Zendesk\ZendeskMessage;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    /** @var \NotificationChannels\Zendesk\ZendeskMessage */
    protected $message;

    public function setUp()
    {
        parent::setUp();

        $this->message = new ZendeskMessage();
    }

    /** @test */
    public function it_accepts_a_name_when_constructing_a_message()
    {
        $message = new ZendeskMessage('Subject');

        $this->assertEquals('Subject', Arr::get($message->toArray(), 'subject'));
    }

    /** @test */
    public function it_provides_a_create_method()
    {
        $message = ZendeskMessage::create('Subject');

        $this->assertEquals('Subject', Arr::get($message->toArray(), 'subject'));
    }

    /** @test */
    public function it_can_set_the_requester()
    {
        $this->message->from('Client Name', 'eamil@example.org');

        $this->assertEquals('Client Name', Arr::get($this->message->toArray(), 'requester.name'));
    }

    /** @test */
    public function it_can_set_the_message_as_public()
    {
        $this->message->visible();

        $this->assertTrue(Arr::get($this->message->toArray(), 'comment.public'));
    }
}
