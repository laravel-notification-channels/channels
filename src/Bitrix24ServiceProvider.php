<?php

namespace NotificationChannels\Bitrix24;

use Illuminate\Support\ServiceProvider;

class Bitrix24ServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([__DIR__.'/../config/' => config_path().'/']);
    }
}