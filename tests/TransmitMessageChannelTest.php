<?php

namespace NotificationChannels\TransmitMessage\Test;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery as m;
use NotificationChannels\TransmitMessage\TransmitMessageChannel;
use NotificationChannels\TransmitMessage\TransmitMessageMessage;
use TransmitMessageLib\TransmitMessageClient;

class TransmitMessageChannelTest extends TestCase
{
    /** @test */

    public function testSmsIsSentViaTransmitMessage()
    {
        $notification = new NotificationTransmitMessageChannelTestNotification;
        $notifiable = new NotificationTransmitMessageChannelTestNotifiable;

        $channel = new TransmitMessageChannel(
            $transmitmessage = m::mock(TransmitMessageClient::class)
        );
        $transmitmessage->shouldReceive('getSMS')->once();


        $channel->send($notifiable, $notification);
    }
}

class NotificationTransmitMessageChannelTestNotification extends Notification
{
    public function toTransmitMessage($notifiable)
    {
        return (new TransmitMessageMessage('this is my message'))
        ->setRecipient('63948123456')
        ->setSender('12345');
    }
}

class NotificationTransmitMessageChannelTestNotifiable
{
    use Notifiable;

    public $message = 'Hello SMS';

    public function routeNotificationForTransmitMessage($notification)
    {
        return $this->message;
    }
}