<?php

namespace NotificationChannels\Sailthru;

use Illuminate\Support\ServiceProvider;

class SailthruServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(SailthruChannel::class)
            ->needs(\Sailthru_Client::class)
            ->give(function () {
                return new \Sailthru_Client(
                    config('services.sailthru.api_key'),
                    config('services.sailthru.secret')
                );
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
