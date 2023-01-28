<?php

namespace NotificationChannels\ClickSend\Test\Feature;

use ClickSend\Api\SMSApi as Client;

class ClientAccountCredentialsTest extends FeatureTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('clicksend.account_username', 'my_username');
        $app['config']->set('clicksend.account_password', 'my_account_password');
    }

    public function testClientCreatedWithBasicAPICredentials()
    {
        $credentials = $this->app->make(Client::class)->getConfig();

        $this->assertEquals(['my_username', 'my_account_password'],
            [$credentials->getUsername(), $credentials->getPassword()]);
    }
}
