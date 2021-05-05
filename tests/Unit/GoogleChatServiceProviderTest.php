<?php

namespace NotificationChannels\GoogleChat\Tests;

use NotificationChannels\GoogleChat\GoogleChatServiceProvider;
use PHPUnit\Framework\TestCase;

class GoogleChatServiceProviderTest extends TestCase
{
    public function test_it_publishes_configuration()
    {
        $provider = $this->createPartialMock(GoogleChatServiceProvider::class, ['publishes']);

        $provider->expects($this->once())
            ->method('publishes')
            ->with([
                realpath(__DIR__.'/../../config/google-chat.php') => config_path('google-chat.php'),
            ], 'google-chat-config')
            ->willReturnSelf();

        $provider->boot();
    }

    public function test_it_merges_configuration()
    {
        $provider = $this->createPartialMock(GoogleChatServiceProvider::class, ['mergeConfigFrom']);

        $provider->expects($this->once())
            ->method('mergeConfigFrom')
            ->with(
                realpath(__DIR__.'/../../config/google-chat.php'),
                'google-chat'
            )
            ->willReturnSelf();

        $provider->register();
    }
}
