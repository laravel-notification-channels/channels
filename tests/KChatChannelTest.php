<?php

namespace NotificationChannels\KChat\Tests;

use GuzzleHttp\Psr7\Response;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\KChat\Exceptions\CouldNotSendNotification;
use NotificationChannels\KChat\KChat;
use NotificationChannels\KChat\KChatChannel;
use NotificationChannels\KChat\KChatMessage;
use PHPUnit\Framework\TestCase;

/**
 * Class MicrosoftTeamsChannelTest.
 */
class KChatChannelTest extends TestCase
{
    /** @var Mockery\Mock */
    protected $kChat;

    public function setUp(): void
    {
        parent::setUp();
        $this->kChat = Mockery::mock(KChat::class);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $this->kChat->shouldReceive('send')
            ->with(
                [
                    'channel_id' => '123456789',
                    'message' => 'This is my content.',
                ])
            ->once()
            ->andReturn(new Response(200));

        $channel = new KChatChannel($this->kChat);

        $response = $channel->send(new TestNotifiable(), new TestNotification());
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_does_not_send_a_notification_if_the_notifiable_does_not_provide_a_kchat_channel()
    {
        $this->expectException(CouldNotSendNotification::class);

        $channel = new KChatChannel($this->kChat);
        $channel->send(new TestNotifiableWithoutRoute(), new TestNotificationNoChannelID());
    }

    /** @test */
    public function it_does_send_a_notification_if_the_notifiable_does_not_provide_a_kchat_channel_but_the_to_param_is_set()
    {
        $this->kChat->shouldReceive('send')
            ->with(
                [
                    'channel_id' => '123456789',
                    'message' => 'This is my content.',
                ])
            ->once()
            ->andReturn(new Response(200));

        $channel = new KChatChannel($this->kChat);

        $response = $channel->send(new TestNotifiableWithoutRoute(), new TestNotificationWithToParam());
        $this->assertEquals(200, $response->getStatusCode());
    }
}

/**
 * Class TestNotifiable.
 */
class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForKChat(Notification $notification = null)
    {
        return '123456789';
    }
}

/**
 * Class TestNotifiableWithoutRoute.
 */
class TestNotifiableWithoutRoute
{
    use Notifiable;
}

/**
 * Class TestNotification.
 */
class TestNotification extends Notification
{
    public function toKChat()
    {
        return (new KChatMessage())
            ->content('This is my content.');
    }
}

/**
 * Class TestNotificationNoChannelID.
 */
class TestNotificationNoChannelID extends Notification
{
    /**
     * @param  $notifiable
     * @return KChatMessage
     */
    public function toKChat($notifiable): KChatMessage
    {
        return (new KChatMessage())
            ->content('This is my content.');
    }
}

/**
 * Class TestNotificationWithToParam.
 */
class TestNotificationWithToParam extends Notification
{
    /**
     * @param  $notifiable
     * @return KChatMessage
     */
    public function toKChat($notifiable): KChatMessage
    {
        return (new KChatMessage())
            ->content('This is my content.')
            ->to('123456789');
    }
}
