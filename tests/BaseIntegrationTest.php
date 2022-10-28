<?php

namespace NotificationChannels\MstatGr\Tests\Integration;

use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use NotificationChannels\MstatGr\Exceptions\InvalidParameter;
use NotificationChannels\MstatGr\MstatGrClient;
use NotificationChannels\MstatGr\MstatGrMessage;
use Orchestra\Testbench\TestCase;

class BaseIntegrationTest extends TestCase
{
    /** @test */
    public function it_returns_correct_message_only_with_content()
    {
        $message = new MstatGrMessage('this is text');
        $this->assertEquals('this is text', $message->content);
    }

    /** @test */
    public function it_returns_correct_from_when_not_provided()
    {
        Config::set('services.mstat.default_from', 'sender');

        $message = new MstatGrMessage('this is text');
        $this->assertEquals('sender', $message->from);
    }

    /** @test */
    public function it_returns_correct_from_when_provided()
    {
        Config::set('services.mstat.default_from', 'sender');

        $message = (new MstatGrMessage('this is text'))
            ->from('custom');
        $this->assertEquals('custom', $message->from);
    }

    /** @test */
    public function it_returns_exception_for_wrong_sender()
    {
        Config::set('services.mstat.default_from', 'sender_with_many_chars');

        $this->assertThrows(function () {
            (new MstatGrMessage('this is text'));
        }, InvalidParameter::class);

        Config::set('services.mstat.default_from', '12345678901234567890');

        $this->assertThrows(function () {
            (new MstatGrMessage('this is text'));
        }, InvalidParameter::class);
    }

    /** @test */
    public function it_returns_correct_exceptions_after_send()
    {
        Http::fake([
            'https://corpsms-api.m-stat.gr/http/sms.php' => Http::response('test 12', 500),
        ]);

        $this->assertThrows(function () {
            $message = (new MstatGrMessage('this is text'));
            (new MstatGrClient())->send(new User(), $message);
        }, \Exception::class);

        Http::fake([
            'https://corpsms-api.m-stat.gr/http/sms.php' => Http::response('test 12', 200),
        ]);

        $this->assertThrows(function () {
            $message = (new MstatGrMessage('this is text'));
            (new MstatGrClient())->send(new User(), $message);
        }, \Exception::class);
    }

    /** @test */
    public function it_sends_successfully_sms()
    {
        Http::fake([
            'https://corpsms-api.m-stat.gr/http/sms.php' => Http::response("OK;1666991968654732;1\n", 200),
        ]);

        $message = (new MstatGrMessage('this is text'));
        $response = (new MstatGrClient())->send(new TestUser(), $message);

        $this->assertEquals(1, $response['credits']);
        $this->assertEquals(true, $response['is_successful']);
    }
}

class TestUser extends User
{
    use Notifiable;
}
