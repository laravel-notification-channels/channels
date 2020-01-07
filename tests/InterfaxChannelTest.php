<?php

namespace NotificationChannels\Interfax\Test;

use Illuminate\Notifications\Notification;
use Interfax\Client as InterfaxClient;
use Interfax\Resource as InterfaxResource;
use Mockery;
use NotificationChannels\Interfax\Exceptions\CouldNotSendNotification;
use NotificationChannels\Interfax\InterfaxChannel;
use NotificationChannels\Interfax\InterfaxMessage;

class InterfaxChannelTest extends TestCase
{
    /** @var Mockery\Mock */
    protected $interfax;

    /** @var Mockery\Mock */
    protected $resource;

    /** @var \NotificationChannels\Interfax\InterfaxChannel */
    protected $channel;

    public function setUp(): void
    {
        parent::setUp();

        config([
            'services.interfax.interval' => 1,
        ]);

        $this->interfax = Mockery::mock(InterfaxClient::class);
        $this->resource = Mockery::mock(InterfaxResource::class);
        $this->channel = new InterfaxChannel($this->interfax);
    }

    /** @test */
    public function it_can_send_notification_with_a_single_file()
    {
        $this->interfax->shouldReceive('deliver')
            ->once()
            ->with([
                'faxNumber' => '12345678901',
                'files' => ['test-file.pdf'],
            ]);

        $this->channel->send(new TestNotifiable, new TestNotificationWithSingleFile);
    }

    /** @test */
    public function it_can_send_notification_with_files()
    {
        $this->interfax->shouldReceive('deliver')
            ->once()
            ->with([
                'faxNumber' => '12345678901',
                'files' => ['test-file-1.pdf', 'test-file-2.pdf'],
            ]);

        $this->channel->send(new TestNotifiable, new TestNotificationWithFiles);
    }

    /** @test */
    public function it_can_send_notification_as_stream()
    {
        $this->interfax->shouldReceive('deliver')
            ->once()
            ->with([
                'faxNumber' => '12345678901',
                'files' => [
                    [
                        'test-file-contents',
                        [
                            'name' => 'test-file.pdf',
                            'mime_type' => 'application/pdf',
                        ],
                    ],
                ],
            ]);

        $this->assertNull($this->channel->send(new TestNotifiable, new TestNotificationAsStream));
    }

    /** @test */
    public function it_can_return_early_when_no_fax_number_provided()
    {
        $this->assertNull($this->channel->send(new TestNotifiableNotSendable, new TestNotificationWithFiles));
    }

    /** @test */
    public function it_can_refresh_the_file_response()
    {
        $this->resource
             ->shouldReceive('refresh')
             ->andReturn((object) [
                 'status' => -1,
             ], (object) [
                 'status' => 0,
             ]);

        $this->interfax
             ->shouldReceive('deliver')
             ->andReturn($this->resource);

        $this->channel->send(new TestNotifiable, new TestNotificationWithRefresh);
    }

    /** @test */
    public function it_can_throw_the_exception()
    {
        $this->expectException(CouldNotSendNotification::class);

        $this->resource
             ->shouldReceive('refresh')
             ->andReturn(new TestResource);

        $this->interfax
             ->shouldReceive('deliver')
             ->andReturn($this->resource);

        $this->channel->send(new TestNotifiable, new TestNotificationWithRefresh);
    }
}

class TestNotificationWithSingleFile extends Notification
{
    /**
     * @param $notifiable
     * @return InterfaxMessage
     * @throws CouldNotSendNotification
     */
    public function toInterfax($notifiable)
    {
        return (new InterfaxMessage)
                    ->user($notifiable)
                    ->file('test-file.pdf');
    }
}

class TestNotificationWithFiles extends Notification
{
    /**
     * @param $notifiable
     * @return InterfaxMessage
     * @throws CouldNotSendNotification
     */
    public function toInterfax($notifiable)
    {
        return (new InterfaxMessage)
                    ->user($notifiable)
                    ->files(['test-file-1.pdf', 'test-file-2.pdf']);
    }
}

class TestNotificationAsStream extends Notification
{
    /**
     * @param $notifiable
     * @return InterfaxMessage
     * @throws CouldNotSendNotification
     */
    public function toInterfax($notifiable)
    {
        return (new InterfaxMessage)
                    ->user($notifiable)
                    ->stream('test-file-contents', 'test-file.pdf');
    }
}

class TestNotificationWithRefresh extends Notification
{
    /**
     * @param $notifiable
     * @return InterfaxMessage
     * @throws CouldNotSendNotification
     */
    public function toInterfax($notifiable)
    {
        return (new InterfaxMessage)
                    ->checkStatus()
                    ->user($notifiable)
                    ->files(['test-file-1.pdf', 'test-file-2.pdf']);
    }
}

class TestResource
{
    public $status = 500;

    public function attributes()
    {
        return [
            'status' => $this->status,
            'message' => 'Failed to send.',
        ];
    }
}
