<?php

namespace NotificationChannels;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class HangoutsChatChannelServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('hangoutsChat', function ($app) {
                return new HangoutsChatChannel($app->make(HttpClient::class));
            });
        });
    }
}
