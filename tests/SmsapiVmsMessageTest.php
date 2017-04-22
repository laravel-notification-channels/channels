<?php

namespace NotificationChannels\Smsapi\Tests;

use NotificationChannels\Smsapi\SmsapiVmsMessage;

/**
 * @internal
 */
class SmsapiVmsMessageTest extends SmsapiMessageTest
{
    public function setUp()
    {
        parent::setUp();
        $this->message = new SmsapiVmsMessage();
    }

    /**
     * @return array
     */
    public function provideBool()
    {
        return [
            'true' => [true],
            'false' => [false],
        ];
    }

    /** @test */
    public function set_file()
    {
        $this->message->file('/file');
        $this->assertEquals('/file', $this->message->data['file']);
    }

    /** @test */
    public function set_tts()
    {
        $this->message->tts('Text to speech');
        $this->assertEquals('Text to speech', $this->message->data['tts']);
    }

    /** @test */
    public function set_tts_lector()
    {
        $this->message->ttsLector('Agnieszka');
        $this->assertEquals('Agnieszka', $this->message->data['tts_lector']);
    }

    /** @test */
    public function set_from()
    {
        $this->message->from('Eco');
        $this->assertEquals('Eco', $this->message->data['from']);
    }

    /** @test */
    public function set_try()
    {
        $this->message->try(3);
        $this->assertEquals(3, $this->message->data['try']);
    }

    /** @test */
    public function set_interval()
    {
        $this->message->interval(3000);
        $this->assertEquals(3000, $this->message->data['interval']);
    }

    /**
     * @test
     * @dataProvider provideBool
     *
     * @param bool $fast
     */
    public function set_skip_gsm($skipGsm)
    {
        $this->message->skipGsm($skipGsm);
        $this->assertEquals($skipGsm, $this->message->data['skip_gsm']);
    }
}
