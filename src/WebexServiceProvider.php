<?php

namespace NotificationChannels\Webex;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class WebexServiceProvider extends ServiceProvider
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
        $this->app->bind(WebexChannel::class, function ($app) {
            return new WebexChannel(
                new HttpClient(),
                $app['config']['services.webex.notification_channel_url'],
                $app['config']['services.webex.notification_channel_id'],
                $app['config']['services.webex.notification_channel_token']
            );
        });

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('webex', function ($app) {
                return $app->make(WebexChannel::class);
            });
        });
    }
}
