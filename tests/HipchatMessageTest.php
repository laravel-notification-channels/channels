<?php

namespace NotificationChannels\Hipchat\Test;

use NotificationChannels\Hipchat\HipchatMessage;

class HipchatMessageTest extends \PHPUnit_Framework_TestCase
{
    public function test_it_can_be_instantiated()
    {
        $message = new HipchatMessage;

        $this->assertInstanceOf(HipchatMessage::class, $message);
    }

    public function test_it_can_accept_content_when_created()
    {
        $message = new HipchatMessage('Foo');

        $this->assertEquals('Foo', $message->content);
    }

    public function test_it_sets_proper_defaults_when_instantiated()
    {
        $message = new HipchatMessage;

        $this->assertEquals('text', $message->format);
        $this->assertEquals('info', $message->level);
        $this->assertEquals('gray', $message->color);
        $this->assertFalse($message->notify);
    }

    public function test_it_can_set_room()
    {
        $message = (new HipchatMessage)
            ->room('Room');

        $this->assertEquals('Room', $message->room);
    }

    public function test_it_can_set_from()
    {
        $message = (new HipchatMessage)
            ->from('Bar');

        $this->assertEquals('Bar', $message->from);
    }

    public function test_it_can_set_text_content()
    {
        $message = (new HipchatMessage)
            ->content('Foo Bar');

        $this->assertEquals('Foo Bar', $message->content);
    }

    public function test_it_can_set_html_content()
    {
        $message = (new HipchatMessage)
            ->content('<strong>Foo</strong> Bar');

        $this->assertEquals('<strong>Foo</strong> Bar', $message->content);
    }

    public function test_it_can_set_color()
    {
        $message = (new HipchatMessage)
            ->color('yellow');

        $this->assertEquals('yellow', $message->color);
    }

    public function test_it_can_set_text_format()
    {
        $message = (new HipchatMessage)
            ->text();

        $this->assertEquals('text', $message->format);
    }

    public function test_it_can_set_html_format()
    {
        $message = (new HipchatMessage)
            ->html();

        $this->assertEquals('html', $message->format);
    }

    public function test_it_can_set_notify_flag()
    {
        $message = (new HipchatMessage)
            ->notify();

        $this->assertTrue($message->notify);

        $message->notify(false);

        $this->assertFalse($message->notify);
    }

    public function test_it_can_set_info_level()
    {
        $message = (new HipchatMessage)
            ->info();

        $this->assertEquals('info', $message->level);
        $this->assertEquals('gray', $message->color);
    }

    public function test_it_can_set_success_level()
    {
        $message = (new HipchatMessage)
            ->success();

        $this->assertEquals('success', $message->level);
        $this->assertEquals('green', $message->color);
    }

    public function test_it_can_set_error_level()
    {
        $message = (new HipchatMessage)
            ->error();

        $this->assertEquals('error', $message->level);
        $this->assertEquals('red', $message->color);
    }

    public function test_it_transforms_to_array()
    {
        $message = (new HipchatMessage)
            ->from('Bar')
            ->error()
            ->html()
            ->content('<strong>Foo</strong>')
            ->notify();
        
        $this->assertEquals([
            'from' => 'Bar',
            'message_format' => 'html',
            'color' => 'red',
            'notify' => true,
            'message' => '<strong>Foo</strong>',
        ], $message->toArray());
    }
}
