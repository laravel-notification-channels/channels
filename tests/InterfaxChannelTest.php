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
    public function it_can_send_notification_pdf_as_stream()
    {
        $this->interfax->shouldReceive('deliver')
            ->with(Mockery::on(function($output) {
                if($output['faxNumber']!=='12345678901')
                    return false;

                $file = $output['files'][0];

                if($file[1]['name']!==app('filesystem')->path('test-file.pdf'))
                    return false;

                if($file[1]['mime_type']!=='application/pdf')
                    return false;

                if(!is_resource($file[0]))
                    return false;

                return true;
            }));

        $this->channel->send(new TestNotifiable, new TestNotificationAsStreamPdf);
    }

    /** @test */
    public function it_can_send_notification_html_as_stream()
    {
        $filename = 'test-file.html';
        $this->addFile($filename);

        app('filesystem')->put($filename, '<html><body><h1>Test file contents</h1></body></html>');

        $this->interfax->shouldReceive('deliver')
            ->with(Mockery::on(function($output) {
                if($output['faxNumber']!=='12345678901')
                    return false;

                $file = $output['files'][0];

                if($file[1]['name']!==app('filesystem')->path('test-file.html'))
                    return false;

                if($file[1]['mime_type']!=='text/html')
                    return false;

                if(!is_resource($file[0]))
                    return false;

                return true;
            }));

        $this->channel->send(new TestNotifiable, new TestNotificationAsStreamHtml);
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

class TestNotificationAsStreamPdf extends Notification
{
    /**
     * @param $notifiable
     * @return InterfaxMessage
     * @throws CouldNotSendNotification
     */
    public function toInterfax($notifiable)
    {
        $path = app('filesystem')->path('test-file.pdf');

        return (new InterfaxMessage)
                    ->user($notifiable)
                    ->stream(fopen($path, 'r'), $path);
    }
}

class TestNotificationAsStreamHtml extends Notification
{
    /**
     * @param $notifiable
     * @return InterfaxMessage
     * @throws CouldNotSendNotification
     */
    public function toInterfax($notifiable)
    {
        $path = app('filesystem')->path('test-file.html');

        return (new InterfaxMessage)
                    ->user($notifiable)
                    ->stream(fopen($path, 'r'), $path);
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
