<?php

namespace NotificationChannels\ClickSend\Test\Feature;

use ClickSend\Api\SMSApi as Client;
use RuntimeException;

class NoClickSendConfigurationTest extends FeatureTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('clicksend.api_key', 'my_api_key');
    }

    public function testWhenNoConfigurationIsGivenExceptionIsRaised()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Please provide your ClickSend API credentials.
            Possible combinations: api_username + api_key OR account_username + account_password.');
        app(Client::class);
    }
}
