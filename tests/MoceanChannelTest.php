<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/2/2019
 * Time: 5:27 PM.
 */

namespace NotificationChannels\MoceanApi\Test;

use NotificationChannels\MoceanApi\MoceanApiChannel;
use Prophecy\Argument;
use PHPUnit\Framework\TestCase;
use NotificationChannels\MoceanApi\Test\Dummy\DummyNotifiable;
use NotificationChannels\MoceanApi\Test\Dummy\MockMoceanClient;
use NotificationChannels\MoceanApi\Test\Dummy\DummyNotificationClass;
use NotificationChannels\MoceanApi\Test\Dummy\DummyCustomNotificationClass;
use Mocean\Message\Client as MoceanClient;

class MoceanChannelTest extends TestCase
{
    public function testSmsSentViaMoceanChannel()
    {
        $notification = new DummyNotificationClass();
        $notifiable = new DummyNotifiable();

        $mockMocean = $this->prophesize(MockMoceanClient::class);
        $mockMessageClient = $this->prophesize(MoceanClient::class);

        $channel = new MoceanApiChannel($mockMocean->reveal());

        $mockMessageClient->send(Argument::that(function ($params) {
            $params = $params->getRequestData();

            $this->assertEquals($params['mocean-from'], 'MoceanApi');
            $this->assertEquals($params['mocean-to'], '60123456789');
            $this->assertEquals($params['mocean-text'], 'testing message');

            return true;
        }))->shouldBeCalledTimes(1)->willReturn(null);
        $mockMocean->message()->shouldBeCalledTimes(1)->willReturn($mockMessageClient->reveal());

        $channel->send($notifiable, $notification);
    }

    public function testSmsSentViaMoceanChannelWithCustomConfiguration()
    {
        $notification = new DummyCustomNotificationClass();
        $notifiable = new DummyNotifiable();

        $mockMocean = $this->prophesize(MockMoceanClient::class);
        $mockMessageClient = $this->prophesize(MoceanClient::class);

        $channel = new MoceanApiChannel($mockMocean->reveal());

        $mockMessageClient->send(Argument::that(function ($params) {
            $params = $params->getRequestData();

            $this->assertEquals($params['mocean-from'], 'MoceanApi');
            $this->assertEquals($params['mocean-to'], '60123456789');
            $this->assertEquals($params['mocean-text'], 'Hello World');
            $this->assertEquals($params['mocean-dlr-url'], 'http://test.com');

            return true;
        }))->shouldBeCalledTimes(1)->willReturn(null);
        $mockMocean->message()->shouldBeCalledTimes(1)->willReturn($mockMessageClient->reveal());

        $channel->send($notifiable, $notification);
    }
}
