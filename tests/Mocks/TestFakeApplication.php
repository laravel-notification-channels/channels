<?php

namespace FtwSoft\NotificationChannels\Intercom\Tests\Mocks;

use Closure;
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
    public function basePath($path = '')
    {
    }

    /**
     * {@inheritdoc}
     */
    public function environment(...$environments)
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
    public function register($provider, $options = [], $force = false)
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

    /**
     * {@inheritdoc}
     */
    public function bootstrapPath($path = '')
    {
    }

    /**
     * {@inheritdoc}
     */
    public function configPath($path = '')
    {
    }

    /**
     * {@inheritdoc}
     */
    public function databasePath($path = '')
    {
    }

    /**
     * {@inheritdoc}
     */
    public function environmentPath()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function resourcePath($path = '')
    {
    }

    /**
     * {@inheritdoc}
     */
    public function storagePath()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function resolveProvider($provider)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function bootstrapWith(array $bootstrappers)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function configurationIsCached()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function detectEnvironment(Closure $callback)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function environmentFile()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function environmentFilePath()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getCachedConfigPath()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getCachedRoutesPath()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getProviders($provider)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function hasBeenBootstrapped()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function loadDeferredProviders()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function loadEnvironmentFrom($file)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function routesAreCached()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function setLocale($locale)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function shouldSkipMiddleware()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function terminate()
    {
    }
}
