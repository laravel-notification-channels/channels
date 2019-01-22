<?php

namespace NotificationChannels\Pushmix\Test;

use Mockery;
use Orchestra\Testbench\TestCase;
use NotificationChannels\Pushmix\PushmixMessage;
use NotificationChannels\Pushmix\Exceptions\MissingParameter;

class MessageTest extends TestCase
{
    /** @var Mockery\Mock */
    protected $message;

    public function setUp()
    {
        parent::setUp();

        $this->message = Mockery::mock(PushmixMessage::class);
    }

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_set_button()
    {
        $this->message->shouldReceive('button')
            ->once()
            ->with('Button One', 'https://www.pushmix.co.uk')
            ->andReturnSelf();

        $this->assertEquals($this->message, $this->message->button('Button One', 'https://www.pushmix.co.uk'));

        // Testing One button
        $buttons = [
                      [
                        'action_title_one'  => 'Button One',
                        'action_url_one'    => 'https://www.pushmix.co.uk',
                      ],
                  ];

        $m = PushmixMessage::create('all')
          ->button('Button One', 'https://www.pushmix.co.uk');

        $this->assertEquals($buttons, $m->getButtons());

        unset($m);

        // Testing Two Buttons
        array_push($buttons, [
                              'action_title_two'  => 'Button Two',
                              'action_url_two'    => 'https://www.pushmix.co.uk/docs',
                            ]);

        $m = PushmixMessage::create('all')
          ->button('Button One', 'https://www.pushmix.co.uk')
          ->button('Button Two', 'https://www.pushmix.co.uk/docs');

        $this->assertEquals($buttons, $m->getButtons());
        unset($m);

        // Only Two buttons allowed
        $m = PushmixMessage::create('all')
          ->button('Button One', 'https://www.pushmix.co.uk')
          ->button('Button Two', 'https://www.pushmix.co.uk/docs')
          ->button('Button Three', 'https://www.pushmix.co.uk/api');

        $this->assertEquals($buttons, $m->getButtons());
    }

    /***/

    /** @test */
    public function it_can_method_chaining()
    {
        $d = ['to', 'title', 'body', 'url', 'ttl', 'priority', 'icon', 'badge', 'image'];
        foreach ($d as $val) {
            $this->message->shouldReceive($val)
              ->once()
              ->with('Hello')
              ->andReturnSelf();

            $this->assertEquals($this->message, $this->message->$val('Hello'));
        }
    }

    /***/

    /** @test */
    public function it_can_set_topic()
    {
        $m = PushmixMessage::create('')->to('all');
        $this->assertSame('all', $m->to);
    }

    /***/

    /** @test */
    public function it_can_set_title()
    {
        $m = PushmixMessage::create('all')->title('Hello');
        $this->assertSame('Hello', $m->title);
    }

    /***/

    /** @test */
    public function it_can_set_body()
    {
        $m = PushmixMessage::create('all')->body('This is body');
        $this->assertSame('This is body', $m->body);
    }

    /***/

    /** @test */
    public function it_can_set_url()
    {
        $m = PushmixMessage::create('all')->url('https://www.pushmix.co.uk');
        $this->assertSame('https://www.pushmix.co.uk', $m->default_url);
    }

    /***/

    /** @test */
    public function it_can_set_ttl()
    {
        $m = PushmixMessage::create('all')->ttl(3600);
        $this->assertSame(3600, $m->time_to_live);
    }

    /***/

    /** @test */
    public function it_can_set_priority()
    {
        $m = PushmixMessage::create('all')->priority('high');
        $this->assertSame('high', $m->priority);
    }

    /***/

    /** @test */
    public function it_can_set_icon()
    {
        $m = PushmixMessage::create('all')->icon('https://www.pushmix.co.uk/media/favicons/apple-touch-icon.png');
        $this->assertSame('https://www.pushmix.co.uk/media/favicons/apple-touch-icon.png', $m->icon);
    }

    /***/

    /** @test */
    public function it_can_set_badge()
    {
        $m = PushmixMessage::create('all')->badge('https://www.pushmix.co.uk/media/favicons/apple-touch-icon.png');
        $this->assertSame('https://www.pushmix.co.uk/media/favicons/apple-touch-icon.png', $m->badge);
    }

    /***/

    /** @test */
    public function it_can_set_image()
    {
        $m = PushmixMessage::create('all')->image('https://www.pushmix.co.uk/media/favicons/apple-touch-icon.png');
        $this->assertSame('https://www.pushmix.co.uk/media/favicons/apple-touch-icon.png', $m->image);
    }

    /***/

    /** @test */
    public function it_can_throw_exception_topic()
    {
        $this->expectException(MissingParameter::class);

        $m = PushmixMessage::create('all');
        $m->to = null;
        $m->toArray();
    }

    /***/

    /** @test */
    public function it_can_throw_exception_title()
    {
        $this->expectException(MissingParameter::class);
        PushmixMessage::create('all')->toArray();
    }

    /***/

    /** @test */
    public function it_can_throw_exception_body()
    {
        $this->expectException(MissingParameter::class);
        PushmixMessage::create('all')
        ->title('Hello')
        ->toArray();
    }

    /***/

    /** @test */
    public function it_can_throw_exception_default_url()
    {
        $this->expectException(MissingParameter::class);
        PushmixMessage::create('all')
        ->title('Hello')
        ->body('Welcome to Pushmix!')
        ->toArray();
    }

    /***/

    /** @test */
    public function it_can_return_an_array()
    {
        $arr = PushmixMessage::create('all')
          ->title('Hello')
          ->body('Welcome to Pushmix!')
          ->url('https://www.pushmix.co.uk')
          ->button('Read More', 'https://www.pushmix.co.uk/docs')
          ->button('API', 'https://www.pushmix.co.uk/docs/api')
          ->priority('high')
          ->ttl(7200)
          ->icon('https://www.pushmix.co.uk/media/favicons/apple-touch-icon.png')
          ->badge('https://www.pushmix.co.uk/media/favicons/pm_badge_v2.png')
          ->image('https://www.pushmix.co.uk/media/photos/photo16.jpg')
          ->toArray();

        $this->assertInternalType('array', $arr);
        $this->assertEquals(13, count($arr));
    }

    /***/
}
