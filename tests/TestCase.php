<?php

namespace NotificationChannels\OneWaySms\Tests;

use NotificationChannels\OneWaySms\OneWaySmsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [OneWaySmsServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('services.onewaysms', [
            'endpoint' => 'https://gateway.test',
            'username' => 'test-user',
            'password' => 'test-pass',
            'sender' => 'TESTSENDER',
        ]);
    }
}
