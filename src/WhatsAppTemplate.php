<?php

namespace NotificationChannels\WhatsApp;

use Netflie\WhatsAppCloudApi\Message\Template\Component as CloudApiComponent;
use NotificationChannels\WhatsApp\Component\Component;

class WhatsAppTemplate
{
    private string $to;

    private string $name;

    private string $language;

    private array $components;

    private function __construct($to = '', $name = '', $language = 'en_US')
    {
        $this->to = $to;
        $this->name = $name;
        $this->language = $language;
        $this->components = [
            'header' => [],
            'body' => [],
        ];
    }

    public static function create($to = '', $name = '', $language = 'en_US'): self
    {
        return new self($to, $name, $language);
    }

    public function to(string $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function language(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function header(Component $component): self
    {
        $this->components['header'][] = $component->toArray();

        return $this;
    }

    public function body(Component $component): self
    {
        $this->components['body'][] = $component->toArray();

        return $this;
    }

    public function recipient(): ?string
    {
        return $this->to;
    }

    public function configuredName(): ?string
    {
        return $this->name;
    }

    public function configuredLanguage(): string
    {
        return $this->language;
    }

    public function components(): CloudApiComponent
    {
        return new CloudApiComponent(
            $this->components['header'],
            $this->components['body']
        );
    }

    public function hasRecipient(): bool
    {
        return !empty($this->to);
    }
}
