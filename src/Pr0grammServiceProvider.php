<?php

namespace NotificationChannels\Pr0gramm;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Tschucki\Pr0grammApi\Facades\Pr0grammApi;

class Pr0grammServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->when(Pr0grammChannel::class)
            ->needs(Pr0grammApi::class)
            ->give(function () {
                return Pr0grammApi::class;
            });

    }

    public function register(): void
    {
        Notification::resolved(static function (ChannelManager $service) {
            $service->extend('pr0gramm', static fn ($app) => $app->make(Pr0grammChannel::class));
        });
    }
}
