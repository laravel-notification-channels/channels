<?php

namespace NotificationChannels\AwsSns;

use Aws\Sns\SnsClient as SnsService;
use Illuminate\Support\ServiceProvider;

class SnsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(SnsChannel::class)
            ->needs(Sns::class)
            ->give(function () {
                return new Sns($this->app->make(SnsService::class));
            });

        $this->app->bind(SnsService::class, function() {
            $snsConfig =  $this->app['config']['services.sns'];
            $snsConfig['version'] = 'latest';

            return new SnsService($snsConfig);
        });
    }
}
