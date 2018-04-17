<?php

namespace khaninejad\linkedin;

use Illuminate\Support\ServiceProvider;

class LinkedinServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Bootstrap code here.


         $this->app->when(LinkedinChannel::class)
          ->needs(\Happyr\LinkedIn\LinkedIn::class)
          ->give(function () {
              $linkedIn=new \Happyr\LinkedIn\LinkedIn( config('services.linkedin.app_id'), config('services.linkedin.app_secret'));
              $linkedIn->setAccessToken(config('services.linkedin.access_token'));
              return $linkedIn;
          });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
