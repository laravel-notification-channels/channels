<?php

namespace NotificationChannels\PushoverNotifications;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(Channel::class)
            ->needs(Pushover::class)
            ->give(function () {
                return new Pushover(new HttpClient(), config('services.pushover.token'));
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
