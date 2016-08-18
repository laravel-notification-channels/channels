<?php

namespace NotificationChannels\Ovh;

use Illuminate\Support\ServiceProvider;

class OvhServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->register(Akibatech\Ovhsms\ServiceProvider::class);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
