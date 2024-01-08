<?php

namespace NotificationChannels\Ntfy\Test;

use NotificationChannels\Ntfy\NtfyChannel;
use PHPUnit\Framework\TestCase;

class NtfyChannelTest extends TestCase
{
    public function test_send_should_throw_method_missing_exception()
    {
        $this->expectException(\NotificationChannels\Ntfy\Exceptions\CouldNotSendNotification::class);

        $client = $this->getMockBuilder(\Psr\Http\Client\ClientInterface::class)->getMock();
        $channel = new NtfyChannel(new \NotificationChannels\Ntfy\Ntfy('host', 'port', 'username', 'password', $client));
        $notification = $this->getMockBuilder(\Illuminate\Notifications\Notification::class)->getMock();
        $channel->send(new \stdClass(), $notification);
    }
}
