<?php

namespace NotificationChannels\OneWaySms;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class OneWaySmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OneWaySmsApi::class, static fn ($app) => new OneWaySmsApi(
            $app->make(HttpClient::class),
            $app['config']['services.onewaysms.endpoint'] ?: OneWaySmsApi::DEFAULT_ENDPOINT,
            $app['config']['services.onewaysms.username'],
            $app['config']['services.onewaysms.password'],
        ));

        Notification::resolved(static function (ChannelManager $service) {
            $service->extend('one_way_sms', fn ($app) => new OneWaySmsChannel(
                $app->make(OneWaySmsApi::class),
                $app['config']['services.onewaysms.sender'],
            ));
        });
    }
}
