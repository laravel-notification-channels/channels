<?php

namespace NotificationChannels\Infobip\Test;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use NotificationChannels\Infobip\InfobipServiceProvider;
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
            InfobipServiceProvider::class,
        ];
    }
}
