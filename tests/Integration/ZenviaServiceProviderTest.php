<?php

declare(strict_types=1);

namespace NotificationChannels\LaravelZenviaChannel\Tests\Integration;

use NotificationChannels\LaravelZenviaChannel\Exceptions\InvalidConfigException;
use NotificationChannels\LaravelZenviaChannel\ZenviaChannel;

class ZenviaServiceProviderTest extends BaseIntegrationTest
{
    public function testThatApplicationCannotCreateChannelWithoutConfig()
    {
        $this->expectException(InvalidConfigException::class);

        $this->app->get(ZenviaChannel::class);
    }

    public function testThatApplicationCreatesChannelWithConfig()
    {
        $this->app['config']->set('zenvia-notification-channel.account', 'test');
        $this->app['config']->set('zenvia-notification-channel.password', 'password');

        $this->assertInstanceOf(ZenviaChannel::class, $this->app->get(ZenviaChannel::class));
    }
}
