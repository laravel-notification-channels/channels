<?php

namespace NotificationChannels\AllMySms;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;

class AllMySmsServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind(AllMySms::class, function ($app) {
            return new AllMySms(
                new HttpClient(),
                $this->app['config']['services.all_my_sms']
            );
        });

        $this->app->bind(AllMySmsChannel::class, function ($app) {
            return new AllMySmsChannel(
                $this->app->make(AllMySms::class),
                $this->app['config']['services.all_my_sms.sender'],
                $this->app['config']['services.all_my_sms.universal_to']
            );
        });

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('all_my_sms', function ($app) {
                return $this->app->make(AllMySmsChannel::class);
            });
        });
    }
}
