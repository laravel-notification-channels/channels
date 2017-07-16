<?php

namespace NotificationChannels\Orange\Test;

use Mockery;
use Mediumart\Orange\SMS\SMS;
use Mediumart\Orange\SMS\Http\SMSClient;
use NotificationChannels\Orange\OrangeMessage;
use NotificationChannels\Orange\OrangeSMSChannel;

class OrangeSMSChannelTest extends TestCase
{
    /**
     * @test
     */
    public function can_send_message()
    {
        $SMSClient = \Mockery::mock(SMSClient::getInstance());
        $client = \Mockery::spy(SMS::class.'[send]', [$SMSClient]);
        $client->shouldReceive('send')->andReturn(true);

        $channel = new OrangeSMSChannel($client);
        $this->assertTrue($channel->sendMessage(
            (new OrangeMessage())->to('+237690000000')->from('+237690000000')->text('test')
        ));
    }

    /**
     * @test
     */
    public function can_handle_notification()
    {
        $this->assertTrue(OrangeSMSChannel::canHandleNotification('orange'));
    }

    /**
     * @test
     */
    public function create_driver()
    {
        $this->app->instance('orange-sms-client', Mockery::mock(SMSClient::getInstance()));

        $this->assertTrue(method_exists(OrangeSMSChannel::createDriver('orange'), 'send'));
    }
}
