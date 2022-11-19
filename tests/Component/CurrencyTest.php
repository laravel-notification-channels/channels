<?php

namespace NotificationChannels\WhatsApp\Test;

use NotificationChannels\WhatsApp\Component\Currency;
use PHPUnit\Framework\TestCase;

final class CurrencyTest extends TestCase
{
    /** @test */
    public function currency_is_valid()
    {
        $amount = 10.25; // 10,25 €
        $currency = new Currency($amount);
        $expectedValue = [
            'type' => 'currency',
            'currency' => [
                'amount_1000' => 10250,
                'code' => 'EUR',
            ],
        ];

        $this->assertEquals($expectedValue, $currency->toArray());
    }
}
