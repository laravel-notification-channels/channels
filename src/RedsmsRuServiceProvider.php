<?php

namespace NotificationChannels\RedsmsRu;

use Illuminate\Support\ServiceProvider;

class RedsmsRuServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(RedsmsRuApi::class, function () {
            $config = config('services.redsmsru');

            return new RedsmsRuApi($config['login'], $config['secret'], $config['sender']);
        });
    }
}
