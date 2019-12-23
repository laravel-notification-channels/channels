<?php

namespace NotificationChannels\Vodafone;

use Illuminate\Support\ServiceProvider;

class VodafoneServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind(VodafoneClient::class, function () {
            return new VodafoneClient(config('services.vodafone.username'), config('services.vodafone.password'));
        });
    }
}
