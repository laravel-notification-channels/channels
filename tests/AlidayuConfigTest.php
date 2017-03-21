<?php

namespace NotificationChannels\Alidayu\Test;

use NotificationChannels\Alidayu\AlidayuConfig;

class AlidayuConfigTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function itCanAcceptAConfigWhenConstructing()
    {
        $message = new AlidayuConfig(['app_key' => '99999999', 'app_secret' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa']);

        $this->assertEquals('99999999', $message->getAppKey());
        $this->assertEquals('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', $message->getAppSecret());
    }
}
