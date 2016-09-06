<?php

namespace NotificationChannels\Asterisk;

use Illuminate\Support\ServiceProvider;

class AsteriskServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(AsteriskChannel::class)
            ->needs(Asterisk::class)
            ->give(function () {
                return new Asterisk();
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->register(\Enniel\Ami\Providers\AmiServiceProvider::class);
    }
}
