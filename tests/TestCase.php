<?php

namespace NotificationChannels\Orange\Test;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Mediumart\Orange\OrangeServiceProvider::class,
        ];
    }
}
