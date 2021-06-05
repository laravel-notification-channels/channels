<?php

namespace NotificationChannels\Onewaysms\Tests;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use NotificationChannels\Onewaysms\OnewaysmsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use MockeryPHPUnitIntegration;

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            OnewaysmsServiceProvider::class,
        ];
    }
}
