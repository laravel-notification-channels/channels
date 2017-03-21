<?php

namespace NotificationChannels\Alidayu\Test;

use NotificationChannels\Alidayu\AlidayuMessage;

class AlidayuMessageTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function itCanAcceptAContentWhenConstructingAMessage()
    {
        $message = new AlidayuMessage('SMS_99999999', ['code' => 1234], 'TEST');

        $this->assertEquals('SMS_99999999', $message->template);
        $this->assertEquals(['code' => 1234], $message->parameters);
        $this->assertEquals('TEST', $message->signature);
    }

    /** @test */
    public function itCanAcceptAContentWhenCreatingAMessage()
    {
        $message = AlidayuMessage::create('SMS_99999999', ['code' => 1234], 'TEST');

        $this->assertEquals('SMS_99999999', $message->template);
        $this->assertEquals(['code' => 1234], $message->parameters);
        $this->assertEquals('TEST', $message->signature);
    }

    /** @test */
    public function itCanSetTheTemplate()
    {
        $message = (new AlidayuMessage())->template('SMS_99999999');

        $this->assertEquals('SMS_99999999', $message->template);
    }

    /** @test */
    public function itCanSetTheParameters()
    {
        $message = (new AlidayuMessage())->parameters(['code' => 1234]);

        $this->assertEquals(['code' => 1234], $message->parameters);
    }

    /** @test */
    public function itCanSetTheSignature()
    {
        $message = (new AlidayuMessage())->signature('TEST');

        $this->assertEquals('TEST', $message->signature);
    }
}
