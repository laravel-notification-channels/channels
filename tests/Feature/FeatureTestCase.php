<?php

namespace NotificationChannels\ClickSend\Test\Feature;

use NotificationChannels\ClickSend\ClickSendServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class FeatureTestCase extends TestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [ClickSendServiceProvider::class];
    }
}
