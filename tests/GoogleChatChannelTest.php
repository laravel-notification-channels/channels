<?php

namespace NotificationChannels\GoogleChat\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use NotificationChannels\GoogleChat\Exceptions\CouldNotSendNotification;
use NotificationChannels\GoogleChat\GoogleChatChannel;
use NotificationChannels\GoogleChat\Tests\Fixtures\TestNotifiable;
use NotificationChannels\GoogleChat\Tests\Fixtures\TestNotification;

class GoogleChatChannelTest extends TestCase
{
    public function test_it_rejects_sending_when_to_google_chat_method_undefined()
    {
        $notification = $this->createMock(Notification::class);

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('Notification of class: '.get_class($notification).' must define a `toGoogleChat()` method in order to send via the Google Chat Channel');

        $this->newChannel()->send('foo', $notification);
    }

    public function test_it_rejects_sending_when_non_google_chat_message_supplied()
    {
        $notification = $this->createMock(TestNotification::class);
        $notification->expects($this->once())
        ->method('toGoogleChat')
        ->withAnyParameters()
        ->willReturn('This value is invalid, as it is not an instance of Google Chat Message');

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage("Expected a message instance of type NotificationChannels\GoogleChat\GoogleChatMessage - Actually received string");

        $this->newChannel()->send('foo', $notification);
    }

    public function test_it_rejects_sending_when_no_space_configured()
    {
        $notification = $this->newNotification();

        $notifiable = new class {
            use Notifiable;
        };

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('No webhook URL was available when sending the Google Chat notification');

        $this->newChannel()->send($notifiable, $notification);
    }

    public function test_it_sends_to_default_space()
    {
        config(['google-chat.space' => 'https://chat.googleapis.com/default-space']);

        $notifiable = new class {
            use Notifiable;
        };

        $notification = $this->newNotification();

        $response = $this->createMock(Response::class);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->with(
                'post',
                'https://chat.googleapis.com/default-space',
                ['json' => $notification->toGoogleChat($notifiable)->toArray()]
            )
            ->willReturn($response);

        $this->newChannel($client)->send($notifiable, $notification);
    }

    public function test_it_sends_to_notifiable_space()
    {
        config(['google-chat.space' => 'https://chat.googleapis.com/default-space']);

        $notifiable = $this->newNotifiable('https://chat.googleapis.com/notifiable-space');

        $notification = $this->newNotification();

        $response = $this->createMock(Response::class);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->with(
                'post',
                'https://chat.googleapis.com/notifiable-space',
                ['json' => $notification->toGoogleChat($notifiable)->toArray()]
            )
            ->willReturn($response);

        $this->newChannel($client)->send($notifiable, $notification);
    }

    public function test_it_sends_to_notification_space()
    {
        config(['google-chat.space' => 'https://chat.googleapis.com/default-space']);

        $notifiable = $this->newNotifiable('https://chat.googleapis.com/notifiable-space');

        $notification = $this->newNotification()
            ->setSpace('https://chat.googleapis.com/notification-space');

        $response = $this->createMock(Response::class);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->with(
                'post',
                'https://chat.googleapis.com/notification-space',
                ['json' => $notification->toGoogleChat($notifiable)->toArray()]
            )
            ->willReturn($response);

        $this->newChannel($client)->send($notifiable, $notification);
    }

    public function test_it_resolves_space_config_nested_key()
    {
        config(['google-chat.spaces.test' => 'example-alternate-space']);

        $notifiable = $this->newNotifiable('test');

        $notification = $this->newNotification();

        $response = $this->createMock(Response::class);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->with(
                'post',
                'example-alternate-space',
                ['json' => $notification->toGoogleChat($notifiable)->toArray()]
            )
            ->willReturn($response);

        $this->newChannel($client)->send($notifiable, $notification);
    }

    public function test_it_falls_back_to_provided_space_as_uri()
    {
        $notifiable = $this->newNotifiable('//example-fallback-space');

        $notification = $this->newNotification();

        $response = $this->createMock(Response::class);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->with(
                'post',
                '//example-fallback-space',
                ['json' => $notification->toGoogleChat($notifiable)->toArray()]
            )
            ->willReturn($response);

        $this->newChannel($client)->send($notifiable, $notification);
    }

    public function test_it_handles_client_exceptions()
    {
        $notifiable = $this->newNotifiable('//uri');

        $notification = $this->newNotification();

        $exception = new ClientException(
            'Example 400 level HTTP exception',
            $this->createMock(Request::class),
            tap($this->createMock(Response::class), function ($mock) {
                $mock->method('getStatusCode')->willReturn(400);
            }),
        );

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->withAnyParameters()
            ->willThrowException($exception);

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('Failed to send Google Chat message, encountered client error: `400 - Example 400 level HTTP exception`');

        $this->newChannel($client)->send($notifiable, $notification);
    }

    public function test_it_handles_unexpected_exceptions()
    {
        $notifiable = $this->newNotifiable('//uri');

        $notification = $this->newNotification();

        $exception = new \Exception('Example unexpected exception');

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->withAnyParameters()
            ->willThrowException($exception);

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('Failed to send Google Chat message, unexpected exception encountered: `Example unexpected exception`');

        $this->newChannel($client)->send($notifiable, $notification);
    }

    private function newChannel($client = null): GoogleChatChannel
    {
        if (! $client) {
            $client = $this->createMock(Client::class);
        }

        return new GoogleChatChannel($client);
    }

    private function newNotification(): TestNotification
    {
        return new TestNotification;
    }

    private function newNotifiable(string $space = null): TestNotifiable
    {
        return new TestNotifiable($space);
    }
}
