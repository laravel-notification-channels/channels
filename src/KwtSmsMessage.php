<?php

namespace NotificationChannels\KwtSms;

class KwtSmsMessage
{
    protected string $content = '';

    protected ?string $sender = null;

    public static function create(string $content = ''): static
    {
        return (new static())->content($content);
    }

    public function content(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function sender(string $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getSender(): ?string
    {
        return $this->sender;
    }
}
