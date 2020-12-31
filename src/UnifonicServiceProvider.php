<?php

declare(strict_types=1);

namespace NotificationChannels\Unifonic;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\ServiceProvider;
use NotificationChannels\Unifonic\Exceptions\InvalidConfiguration;

class UnifonicServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(UnifonicChannel::class)
            ->needs(UnifonicClient::class)
            ->give(function () {
                if (is_null($appsId = config('services.unifonic.appsId'))) {
                    throw InvalidConfiguration::configurationNotSet();
                }
                return new UnifonicClient(new GuzzleClient(), $appsId);
            });
    }
}
