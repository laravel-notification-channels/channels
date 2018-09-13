<?php

namespace FtwSoft\NotificationChannels\Intercom;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Intercom\IntercomClient;

class IntercomServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(IntercomChannel::class)
            ->needs(IntercomClient::class)
            ->give(function () {
                /* @var Config $config */
                return new IntercomClient(
                    Config::get('services.intercom.token'),
                    null
                );
            });
    }

    /**
     * Register any package services.
     */
    public function register()
    {
        Notification::extend('intercom', function (Application $app) {
            return $app->make(IntercomChannel::class);
        });
    }
}
