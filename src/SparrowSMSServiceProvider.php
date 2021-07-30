<?php

namespace NotificationChannels\SparrowSMS;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class SparrowSMSServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('sparrowsms', function () {
                return new SparrowSMSChannel(config('services.sparrowsms'));
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
