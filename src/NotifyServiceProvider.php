<?php

namespace NotificationChannels\Notify;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class NotifyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(NotifyChannel::class)
            ->needs(NotifyClient::class)
            ->give(function () {
                $config = config('services.notify');
                return new NotifyClient(new Client(), $config);
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
