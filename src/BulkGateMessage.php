<?php

namespace NotificationChannels\BulkGate;

class BulkGateMessage
{
    /** @var string */
    protected $phone_number;

    /** @var string */
    protected $country_code;

    /** @var string */
    private $text;

    public function to(string $phone_number, ?string $country_code = null): self
    {
        $this->phone_number = $phone_number;
        $this->country_code = $country_code;

        return $this;
    }

    public function text(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getMessage(): \BulkGate\Sms\Message
    {
        $message = new \BulkGate\Sms\Message($this->phone_number, $this->text);

        if ($this->country_code) {
            $message->phoneNumber($this->phone_number, $this->country_code);
        }

        return $message;
    }

    public function hasPhoneNumber(): bool
    {
        return null !== $this->phone_number;
    }

    public static function create(string $text, string $phone_number = null, string $country_code = null): self
    {
        $message = new self();

        if (! is_null($phone_number)) {
            $message->to($phone_number, $country_code);
        }

        $message->text($text);

        return $message;
    }
}
