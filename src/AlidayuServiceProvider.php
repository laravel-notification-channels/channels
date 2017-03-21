<?php

namespace NotificationChannels\Alidayu;

use Flc\Alidayu\Client as AlidayuService;
use Illuminate\Support\ServiceProvider;

class AlidayuServiceProvider extends ServiceProvider
{
    private $alidayu;

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(AlidayuChannel::class)
            ->needs(Alidayu::class)
            ->give(function () {
                return new Alidayu(
                    $this->app->make(AlidayuConfig::class)
                );
            });

        $this->app->bind(AlidayuService::class, function () {
            $config = $this->app['config']['services.alidayu'];

            return new AlidayuService($config['app_key'], $config['app_secret']);
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind(AlidayuConfig::class, function () {
            return new AlidayuConfig($this->app['config']['services.alidayu']);
        });
    }
}
