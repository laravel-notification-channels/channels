<?php

namespace NotificationChannels\FortySixElks\Test;

use Mockery;
use Illuminate\Contracts\Events\Dispatcher;
use NotificationChannels\FortySixElks\FortySixElksChannel;

class FortySixElksChannelTest extends \PHPUnit_Framework_TestCase
{
    protected $dispatcher;

    protected $channel;

    public function setUp(){
        parent::setUp();
        $this->dispatcher = new \Illuminate\Events\Dispatcher();

        $this->channel = new FortySixElksChannel($this->dispatcher);
    }

    public function testItCanBeInstantiatedTest(){
        $this->assertInstanceOf(FortySixElksChannel::class, $this->channel);
    }

}
