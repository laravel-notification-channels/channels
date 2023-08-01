<?php

namespace NotificationChannels\KChat;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class KChatServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->when(KChatChannel::class)
            ->needs(KChat::class)
            ->give(function () {
                $token = $this->app->make('config')->get('services.infomaniak_kchat.token');
                $base_url = $this->app->make('config')->get('services.infomaniak_kchat.base_url');

                return new KChat(
                    new Client(),
                    $base_url,
                    $token
                );
            });
    }
}
