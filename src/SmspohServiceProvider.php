<?php

namespace NotificationChannels\Smspoh;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class SmspohServiceProvider extends ServiceProvider
{
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
        $this->app->singleton(SmspohApi::class, static function ($app) {
            return new SmspohApi(config('services.smspoh.token'), new HttpClient());
        });

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('smspoh', function ($app) {
                return new SmspohChannel($app[SmspohApi::class], $this->app['config']['services.smspoh.sender']);
            });
        });
    }
}
