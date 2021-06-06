<?php

namespace NotificationChannels\Onewaysms;

use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\Onewaysms\Exceptions\OnewaysmsException;

class OnewaysmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(OnewaysmsChannel::class)
            ->needs(OnewaysmsClient::class)
            ->give(function () {
                $config = config('services.onewaysms');

                if (is_null($config)) {
                    throw OnewaysmsException::configurationNotSet();
                }

                return new OnewaysmsClient();
            });
    }

    public function register()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('onewaysms', function ($app) {
                return new OnewaysmsChannel(new OnewaysmsClient);
            });
        });
    }
}
