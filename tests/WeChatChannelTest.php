<?php
namespace NotificationChannels\WeChat\Test;

use NotificationChannels\WeChat\WeChatChannel;

class WeChatChannelTest extends TestCase
{
    public function testInstantiation()
    {
        $this->assertInstanceOf(WeChatChannel::class, $this->getChannel());
    }

    protected function getChannel()
    {
        return new WeChatChannel();
    }

}