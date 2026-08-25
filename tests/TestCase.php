<?php

declare(strict_types=1);

namespace NotificationChannels\RuStore\Test;

use NotificationChannels\RuStore\RuStoreServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected string $url = '';

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('ru-store.project_id', env('RUSTORE_PROJECT_ID', 'test'));
        $this->app['config']->set('ru-store.token', env('RUSTORE_TOKEN', 'test'));
    }

    /**
     * @param  $app
     * @return class-string[]
     */
    protected function getPackageProviders($app)
    {
        return [RuStoreServiceProvider::class];
    }
}
