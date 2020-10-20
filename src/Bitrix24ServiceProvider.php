<?php

namespace NotificationChannels\Bitrix24;

use Illuminate\Support\ServiceProvider;

class Bitrix24ServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/../config/' => config_path().'/']);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
