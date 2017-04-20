<?php

namespace NotificationChannels\Smsapi\Tests;

use NotificationChannels\Smsapi\SmsapiMessage;

/**
 * @internal
 */
abstract class SmsapiMessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SmsapiMessage
     */
    protected $message;

    /**
     * @return array
     */
    public function provideTo()
    {
        return [
            'one' => ['48100200300'],
            'bulk' => [['48100200300', '500000000']],
        ];
    }

    /**
     * @test
     * @dataProvider provideTo
     *
     * @param string|string[] $fast
     */
    public function set_to($to)
    {
        $this->message->to($to);
        $this->assertEquals($to, $this->message->data['to']);
    }

    /** @test */
    public function set_group()
    {
        $this->message->group('Test');
        $this->assertEquals('Test', $this->message->data['group']);
    }

    /** @test */
    public function set_date1()
    {
        $this->message->date(1287734110);
        $this->assertEquals(1287734110, $this->message->data['date']);
    }

    /** @test */
    public function set_date2()
    {
        $this->message->date('2012-05-10T08:40:27+00:00');
        $this->assertEquals('2012-05-10T08:40:27+00:00', $this->message->data['date']);
    }

    /** @test */
    public function set_notify_url()
    {
        $this->message->notifyUrl('http://example.com/');
        $this->assertEquals('http://example.com/', $this->message->data['notify_url']);
    }
}
