<?php

namespace NotificationChannels\PersianSms\Tests; // Or YourVendorName\PersianSms\Tests

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use NotificationChannels\PersianSms\PersianSmsServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // You can add any specific setup for your tests here
        // For example, running migrations if your package uses them:
        // $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // $this->artisan('migrate', ['--database' => 'testing'])->run();
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            PersianSmsServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        /*
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        */

        // You can set specific config values for your tests here if needed,
        // though phpunit.xml's <env> variables often take precedence for env() calls in config files.
        // Example:
        // $app['config']->set('persian-sms.default_driver', 'ippanel');
        // $app['config']->set('persian-sms.drivers.ippanel.api_key', 'config_test_api_key');
        // $app['config']->set('persian-sms.drivers.ippanel.sender_number', '+98configsender');
    }
}
