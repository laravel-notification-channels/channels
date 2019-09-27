<?php

namespace FtwSoft\NotificationChannels\Intercom;

use Intercom\IntercomClient;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;

class IntercomServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
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
    public function register(): void
    {
        Notification::extend('intercom', function (Container $app) {
            return $app->make(IntercomChannel::class);
        });
    }
}
