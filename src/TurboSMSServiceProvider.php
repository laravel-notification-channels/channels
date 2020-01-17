<?php

namespace NotificationChannels\TurboSMS;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class TurboSMSServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('turbosms', function ($app) {
                return new TurboSMSChannel(config('services.turbosms'));
            });
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
