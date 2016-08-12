<?php

namespace NotificationChannels\SmsCentre;

use Illuminate\Support\ServiceProvider;

class SmsCentreServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SmsCentre::class, function ($app) {
            $config = $app['config']['services.smscentre'];

            return new SmsCentre($config['login'], $config['secret'], $config['sender']);
        });
    }
}
