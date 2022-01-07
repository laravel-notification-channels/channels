<?php

namespace NotificationChannels\BulkGate\Test\Integration;

use NotificationChannels\BulkGate\BulkGateChannel;
use NotificationChannels\BulkGate\Exceptions\InvalidConfigException;

/**
 * @internal
 * @coversNothing
 */
class BulkGateServiceProviderTest extends BaseIntegration
{
    public function testApplicationCannotCreateChannelWithoutConfig()
    {
        $this->expectException(InvalidConfigException::class);

        $this->app->get(BulkGateChannel::class);
    }

    public function testApplicationCreatesChannelWithConfig()
    {
        $this->app['config']->set('bulkgate-notification-channel.app_id', '1234');
        $this->app['config']->set('bulkgate-notification-channel.app_token', 'password');

        $this->assertInstanceOf(BulkGateChannel::class, $this->app->get(BulkGateChannel::class));
    }
}
