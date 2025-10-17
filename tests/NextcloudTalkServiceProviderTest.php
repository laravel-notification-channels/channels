<?php

namespace NotificationChannels\NextcloudTalk\Test;

use Illuminate\Contracts\Config\Repository;
use NotificationChannels\NextcloudTalk\NextcloudTalk;
use NotificationChannels\NextcloudTalk\NextcloudTalkChannel;

class NextcloudTalkServiceProviderTest extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            \NotificationChannels\NextcloudTalk\NextcloudTalkServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('yourpackage.option', 'default_value');
    }

    protected function defineEnvironment($app)
    {
        tap($app['config'], function (Repository $config) {
            $config->set('services.nextcloudtalk', [
                'url'   => 'https://localhost:8080',
                'username' => 'bot',
                'password'   => 'botpassword',
                'default_channel'   => '4uzqkoqa',
                'one_to_one_channel_name'   => 'Chat with bot',
            ]);

        });
    }

    public function test_binding_works(): void
    {
        $channel =  $this->app->make(NextcloudTalkChannel::class);
        $reflection = new \ReflectionClass($channel);
        $property = $reflection->getProperty('nextcloudTalk');
        $property->setAccessible(true);
        $nextcloudTalk = $property->getValue($channel);
        $this->assertInstanceOf(NextcloudTalk::class, $nextcloudTalk);

    }
}
