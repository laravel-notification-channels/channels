<?php

namespace NotificationChannels\Smsapi\Tests;

use NotificationChannels\Smsapi\SmsapiMmsMessage;

/**
 * @internal
 */
class SmsapiMmsMessageTest extends SmsapiMessageTest
{
    public function setUp()
    {
        parent::setUp();
        $this->message = new SmsapiMmsMessage();
    }

    /** @test */
    public function set_subject()
    {
        $this->message->subject('Subject');
        $this->assertEquals('Subject', $this->message->data['subject']);
    }

    /** @test */
    public function set_template()
    {
        $this->message->smil('<smil></smil>');
        $this->assertEquals('<smil></smil>', $this->message->data['smil']);
    }
}
