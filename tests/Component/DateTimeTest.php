<?php

namespace NotificationChannels\WhatsApp\Test;

use NotificationChannels\WhatsApp\Component\DateTime;
use PHPUnit\Framework\TestCase;

final class DateTimeTest extends TestCase
{
    /** @test */
    public function currency_is_valid()
    {
        $dateTime = new \DateTimeImmutable();
        $currency = new DateTime($dateTime);
        $expectedValue = [
            'type' => 'date_time',
            'date_time' => [
                'fallback_value' => $dateTime->format('Y-m-d H:i:s'),
            ],
        ];

        $this->assertEquals($expectedValue, $currency->toArray());
    }
}
