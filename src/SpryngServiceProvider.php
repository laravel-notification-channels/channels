<?php

namespace NotificationChannels\Spryng;

use Illuminate\Support\ServiceProvider;
use Spryng\SpryngRestApi\Spryng;

class SpryngServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->when(SpryngChannel::class)
            ->needs(Spryng::class)
            ->give(function ($app) {
                if (
                    empty($app['config']['services.spryng.key'])
                ) {
                    throw new \InvalidArgumentException('Missing Spryng config in services');
                }

                return new Spryng(
                    $app['config']['services.spryng.key']
                );
            });
    }

    public function provides(): array
    {
        return [Spryng::class];
    }
}
