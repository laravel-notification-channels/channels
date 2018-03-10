<?php

namespace NotificationChannels\Asana\Test;

use Illuminate\Notifications\Notification;
use NotificationChannels\Asana\AsanaChannel;
use NotificationChannels\Asana\AsanaMessage;
use NotificationChannels\Asana\Exceptions\CouldNotSendNotification;
use NotificationChannels\Asana\Exceptions\InvalidConfiguration;
use Orchestra\Testbench\TestCase;

class ChannelTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_when_it_is_not_configured()
    {
        $this->setExpectedException(InvalidConfiguration::class);

        $channel = new AsanaChannel();
        $channel->send(new TestNotifiable(), new TestNotification());
    }

    /** @test */
    public function it_throws_an_exception_when_it_could_not_send_the_notification()
    {
        $this->setExpectedException(CouldNotSendNotification::class);

        $this->app['config']->set('asana.accessToken', 'AsanaKey');

        $channel = new AsanaChannel();
        $channel->send(new TestNotifiable(), new TestNotification());
    }
}

class TestNotifiable
{
    use \Illuminate\Notifications\Notifiable;
}

class TestNotification extends Notification
{
    public function toAsana($notifiable)
    {
        return
            (new AsanaMessage('AsanaName'))
                ->notes('AsanaNotes')
                ->workspace('x')
                ->projects('x');
    }
}
