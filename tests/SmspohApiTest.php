<?php

namespace NotificationChannels\Smspoh\Tests;

use NotificationChannels\Smspoh\SmspohApi;

class SmspohApiTest extends TestCase
{
    /** @test */
    public function it_has_config_with_default(): void
    {
        $token = 'token';
        $endpoint = 'https://smspoh.com/api/v2/send';

        config()->set('services.smspoh.token', $token);
        config()->set('services.smspoh.endpoint', $endpoint);

        $smspoh = $this->getExtendedSmspohApi($token);

        $this->assertEquals($token, $smspoh->getToken());
        $this->assertEquals($endpoint, $smspoh->getEndpoint());
    }

    private function getExtendedSmspohApi($token)
    {
        return new class($token) extends SmspohApi {
            public function getEndpoint(): string
            {
                return $this->endpoint;
            }

            public function getToken(): string
            {
                return $this->token;
            }
        };
    }
}
