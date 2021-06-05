<?php

namespace NotificationChannels\Onewaysms\Tests;

use NotificationChannels\Onewaysms\OnewaysmsApi;

class OnewaysmsApiTest extends TestCase
{
    /** @test */
    public function it_has_config_with_default(): void
    {
        $user = 'user';
        $pwd = 'pwd';
        $endpoint = 'https://gateway.onewaysms.com.my/api.aspx';

        config()->set('services.onewaysms.user', $user);
        config()->set('services.onewaysms.pwd', $pwd);
        config()->set('services.onewaysms.endpoint', $endpoint);

        config()->set('services.onewaysms.user', $user);
        $onewaysms = $this->getExtendedOnewaysmsApi($user, $pwd);

        $this->assertEquals($user, $onewaysms->getUser());
        $this->assertEquals($pwd, $onewaysms->getPwd());
        $this->assertEquals($endpoint, $onewaysms->getEndpoint());
    }

    private function getExtendedOnewaysmsApi($user, $pwd)
    {
        return new class($user, $pwd) extends OnewaysmsApi
        {
            public function getEndpoint(): string
            {
                return $this->endpoint;
            }

            public function getUser(): string
            {
                return $this->user;
            }

            public function getPwd(): string
            {
                return $this->pwd;
            }
        };
    }
}
