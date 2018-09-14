<?php

namespace FtwSoft\NotificationChannels\Intercom\Tests\Mocks;

use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

class TestFakeApplication extends Container implements ApplicationContract
{
    /**
     * {@inheritdoc}
     */
    public function version()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function basePath()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function environment()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function runningInConsole()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function runningUnitTests()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function isDownForMaintenance()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function registerConfiguredProviders()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function register($provider, $force = false)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function registerDeferredProvider($provider, $service = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function booting($callback)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function booted($callback)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getCachedServicesPath()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getCachedPackagesPath()
    {
    }
}
