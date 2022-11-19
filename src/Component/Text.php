<?php

namespace NotificationChannels\WhatsApp\Component;

class Text extends Component
{
    protected string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function toArray(): array
    {
        return [
            'type' => 'text',
            'text' => $this->text,
        ];
    }
}
