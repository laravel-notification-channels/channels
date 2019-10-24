<?php

namespace NotificationChannels\MoceanApi;

use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;

class MoceanApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('moceanapi', function ($app) {
                return new MoceanApiChannel($app['mocean']);
            });
        });
    }
}
