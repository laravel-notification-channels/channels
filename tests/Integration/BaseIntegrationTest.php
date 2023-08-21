<?php

declare(strict_types=1);

namespace NotificationChannels\LaravelZenviaChannel\Tests\Integration;

use NotificationChannels\LaravelZenviaChannel\ZenviaServiceProvider;
use Orchestra\Testbench\TestCase;

class BaseIntegrationTest extends TestCase
{
    public function getPackageProviders($app)
    {
        return [ZenviaServiceProvider::class];
    }
}
