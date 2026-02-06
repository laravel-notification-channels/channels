<?php

namespace Zilmoney\OnlineCheckWriter\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Zilmoney\OnlineCheckWriter\OnlineCheckWriterServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            OnlineCheckWriterServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('onlinecheckwriter.api_key', 'test-api-key');
        $app['config']->set('onlinecheckwriter.base_url', 'https://api.onlinecheckwriter.com/api/v3');
        $app['config']->set('onlinecheckwriter.default_sender', [
            'name' => 'Test Sender',
            'company' => 'Test Company',
            'address1' => '123 Test St',
            'city' => 'Test City',
            'state' => 'TX',
            'zip' => '75001',
            'phone' => '1234567890',
        ]);
        $app['config']->set('onlinecheckwriter.default_bank_account_id', 'test-bank-account');
    }
}
