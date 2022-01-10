<?php

declare(strict_types=1);

namespace NotificationChannels\BulkGate\Test\Integration;

use NotificationChannels\BulkGate\BulkGateServiceProvider;
use Orchestra\Testbench\TestCase;

/**
 * @internal
 * @coversNothing
 */
class BaseIntegration extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [BulkGateServiceProvider::class];
    }
}
