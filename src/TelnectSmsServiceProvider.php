<?php

namespace NotificationChannels\TelnectSms;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Foundation\Application;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\ServiceProvider;
use NotificationChannels\TelnectSms\Exceptions\InvalidConfigurationException;

class TelnectSmsServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->when(TelnectSmsChannel::class)
            ->needs(TelnectSmsClient::class)
            ->give(function (): TelnectSmsClient {
                if (is_null($apiKey = config('services.telnect_sms.api_key'))) {
                    throw InvalidConfigurationException::configurationNotSet();
                }

                return new TelnectSmsClient(new GuzzleClient, $apiKey);
            });

        $this->app->afterResolving(ChannelManager::class, static function (ChannelManager $manager) {
            $manager->extend('cmsms', static fn (Application $app): TelnectSmsChannel => $app->make(TelnectSmsChannel::class));
        });
    }
}
