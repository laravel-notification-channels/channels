<?php

namespace NotificationChannels\WhatsApp\Component;

class Currency extends Component
{
    protected float $amount;

    /**
     * Currency code as defined in ISO 4217.
     */
    protected string $code;

    public function __construct(float $amount, string $code = 'EUR')
    {
        $this->amount = $amount;
        $this->code = $code;
    }

    public function toArray(): array
    {
        return [
            'type' => 'currency',
            'currency' => [
                'code' => $this->code,
                'amount_1000' => (int) ($this->amount * 1000),
            ],
        ];
    }
}
