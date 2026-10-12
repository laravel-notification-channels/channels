<?php

namespace NotificationChannels\IssueBadge;

use GuzzleHttp\Client;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use NotificationChannels\IssueBadge\Exceptions\InvalidConfiguration;

class IssueBadgeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->app->when(IssueBadgeChannel::class)
            ->needs(IssueBadge::class)
            ->give(function () {
                $config = (array) config('services.issuebadge', []);

                if (empty($config['key'])) {
                    throw InvalidConfiguration::apiKeyNotSet();
                }

                return new IssueBadge($config['key'], $config['url'] ?? null, new Client());
            });

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('issuebadge', fn ($app) => $app->make(IssueBadgeChannel::class));
        });
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
    }
}
