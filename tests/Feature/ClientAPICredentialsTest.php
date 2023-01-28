<?php

namespace NotificationChannels\ClickSend\Test\Feature;

use ClickSend\Api\SMSApi as Client;

class ClientAPICredentialsTest extends FeatureTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('clicksend.api_username', 'my_username');
        $app['config']->set('clicksend.api_key', 'my_api_key');
    }

    public function testClientCreatedWithBasicAPICredentials()
    {
        $credentials = $this->app->make(Client::class)->getConfig();

        $this->assertEquals(['my_username', 'my_api_key'],
            [$credentials->getUsername(), $credentials->getPassword()]);
    }
}
