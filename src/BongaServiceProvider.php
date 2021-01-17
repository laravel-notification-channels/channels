<?php

namespace NotificationChannels\Bonga;

use Illuminate\Support\ServiceProvider;
use NotificationChannels\Bonga\Exceptions\InvalidConfiguration;
use Osen\Bonga\Sms;

class BongaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /**
         * Bootstrap the application services.
         */
        $this->app->when(BongaChannel::class)
            ->needs(Sms::class)
            ->give(function () {
                $client = config('services.bonga.client');
                $key    = config('services.bonga.key');
                $secret = config('services.bonga.secret');
                $service = config('services.bonga.service');
                
                if (is_null($client) || is_null($key)) {
                    throw InvalidConfiguration::configurationNotSet();
                }

                $bonga = new Sms(
                    $client,
                    $secret,
                    $key,
                    $service
                );

                return $bonga;
            });
    }
}
