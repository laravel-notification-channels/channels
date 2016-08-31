<?php

namespace NotificationChannels\Cmsms;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client as GuzzleClient;
use NotificationChannels\Cmsms\CmsmsChannel;
use NotificationChannels\Cmsms\Exceptions\InvalidConfiguration;

class CmsmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(CmsmsChannel::class)
            ->needs(CmsmsClient::class)
            ->give(function () {
                $config = config('services.cmsms');

                if (is_null($config)) {
                    throw InvalidConfiguration::configurationNotSet();
                }

                return new CmsmsClient(new GuzzleClient(), $config['product_token']);
            });
    }
}
