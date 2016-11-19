<?php
/**
 * Author: Hilmi Erdem KEREN
 * Date: 18/11/2016
 * Time: 00:31.
 */
namespace NotificationChannels\JetSMS\Test;

use Carbon\Carbon;
use NotificationChannels\JetSMS\JetSMSMessage;
use NotificationChannels\JetSMS\JetSMSMessageInterface;

class JetSMSMessageTest extends \PHPUnit_Framework_TestCase
{
    private $smsMessage;

    /** @test */
    public function it_should_be_constructed()
    {
        $this->smsMessage = new JetSMSMessage('foo', 'bar', 'baz');
        $this->assertInstanceOf(JetSMSMessageInterface::class, $this->smsMessage);
    }

    /** @test */
    public function it_should_be_constructed_with_date()
    {
        $this->smsMessage = new JetSMSMessage('foo', 'bar', 'baz', Carbon::now());
        $this->assertInstanceOf(JetSMSMessageInterface::class, $this->smsMessage);
    }
}
