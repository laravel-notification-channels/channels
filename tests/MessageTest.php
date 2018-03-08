<?php

namespace NotificationChannels\Asana\Test;

use Mockery;
use DateTime;
use Illuminate\Support\Arr;
use NotificationChannels\Asana\AsanaMessage;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    /** @var \NotificationChannels\Asana\AsanaMessage */
    protected $message;

    public function setUp()
    {
        parent::setUp();

        $this->message = new AsanaMessage('x');
        $this->setDefaultMessageParams($this->message);
    }


    private function setDefaultMessageParams($message) {
        $message->workspace("x");
        $message->projects("x");

    }

    /** @test */
    public function it_accepts_a_name_when_constructing_a_message()
    {
        $message = new AsanaMessage('Name');
        $this->setDefaultMessageParams($message);

        $this->assertEquals('Name', Arr::get($message->toArray(), 'name'));
    }

    /** @test */
    public function it_provides_a_create_method()
    {
        $message = AsanaMessage::create('Name');
        $this->setDefaultMessageParams($message);

        $this->assertEquals('Name', Arr::get($message->toArray(), 'name'));
    }

    /** @test */
    public function it_can_set_the_name()
    {
        $this->message->name('TaskName');

        $this->assertEquals('TaskName', Arr::get($this->message->toArray(), 'name'));
    }

    /** @test */
    public function it_can_set_the_notes()
    {
        $this->message->notes('MyDescription');

        $this->assertEquals('MyDescription', Arr::get($this->message->toArray(), 'notes'));
    }

    /** @test */
    public function it_can_set_a_due_date_from_string()
    {
        $date = "2018-01-31";
        $this->message->dueOn($date);

        $this->assertEquals("2018-01-31", Arr::get($this->message->toArray(), 'due_on'));
    }

    /** @test */
    public function it_can_set_a_due_date_from_datetime()
    {
        $date = new DateTime('tomorrow');
        $this->message->dueOn($date);

        $this->assertEquals($date->format("Y-m-d"), Arr::get($this->message->toArray(), 'due_on'));
    }

    /** @test */
    public function it_can_set_a_workspace()
    {
        $this->message->workspace('4711');

        $this->assertEquals('4711', Arr::get($this->message->toArray(), 'workspace'));
    }

    /** @test */
    public function it_can_set_a_project_from_string()
    {
        $this->message->projects('4711');

        $this->assertEquals('4711', Arr::get($this->message->toArray(), 'projects'));
    }


    /** @test */
    public function it_can_set_a_project_from_array()
    {
        $this->message->projects(['4711', '1337']);

        $this->assertEquals(['4711', '1337'], Arr::get($this->message->toArray(), 'projects'));
    }
}