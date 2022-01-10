<?php

namespace NotificationChannels\BulkGate;

use BulkGate\Message\Connection;
use BulkGate\Sms\Sender;
use BulkGate\Sms\SenderSettings\Gate;
use BulkGate\Sms\SenderSettings\StaticSenderSettings;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class BulkGateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/bulkgate-notification-channel.php', 'bulkgate-notification-channel');

        $this->publishes([
            __DIR__.'/../config/bulkgate-notification-channel.php' => config_path('bulkgate-notification-channel.php'),
        ]);

        $this->app->bind(BulkGateNotificationConfig::class, function (Application $app) {
            return new BulkGateNotificationConfig($app['config']['bulkgate-notification-channel']);
        });

        $this->app->singleton(Sender::class, function (Application $app) {
            /** @var \NotificationChannels\BulkGate\BulkGateNotificationConfig $config */
            $config = $app->make(BulkGateNotificationConfig::class);

            $connection = new Connection($config->getAppId(), $config->getAppToken());

            switch ($config->getSenderType()) {
                case Gate::GATE_OWN_NUMBER:
                case Gate::GATE_TEXT_SENDER:
                    $sender_settings = new StaticSenderSettings($config->getSenderType(), $config->getSenderId());

                    break;
                default:
                    $sender_settings = new StaticSenderSettings($config->getSenderType());
            }

            $sender = new Sender($connection);

            $sender->setSenderSettings($sender_settings);

            $sender->unicode($config->getSendUnicode());

            $sender->setDefaultCountry($config->getDefaultCountry());

            return $sender;
        });

        $this->app->singleton(BulkGateChannel::class, function (Application $app) {
            return new BulkGateChannel($app->make(Sender::class), $app->make(Dispatcher::class));
        });
    }
}
