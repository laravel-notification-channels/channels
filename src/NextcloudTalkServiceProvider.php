<?php

namespace NotificationChannels\NextcloudTalk;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class NextcloudTalkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(NextcloudTalkChannel::class)
        ->needs(NextcloudTalk::class)
        ->give(function () {
            return new NextcloudTalk(
                new Client([
                    'base_uri' => rtrim(Config::get('services.nextcloudtalk.url'), '/'),
                    'auth' => [
                        Config::get('services.nextcloudtalk.username'),
                        Config::get('services.nextcloudtalk.password')],
                    'headers' => [
                        'OCS-APIRequest' => 'true',
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ],
                ]),
                Config::get('services.nextcloudtalk.default_channel'),
                Config::get('services.nextcloudtalk.one_to_one_channel_name'),
            );
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
