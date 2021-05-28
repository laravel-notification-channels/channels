<?php

namespace NotificationChannels\Expo\Test;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use NotificationChannels\Expo\ExpoServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use MockeryPHPUnitIntegration;

    /**
     * @param $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ExpoServiceProvider::class,
        ];
    }
}
