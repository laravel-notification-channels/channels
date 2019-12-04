<?php

namespace NotificationChannels\Smspoh\Tests;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use NotificationChannels\Smspoh\SmspohServiceProvider;
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
            SmspohServiceProvider::class,
        ];
    }
}
