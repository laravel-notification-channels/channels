<?php

namespace NotificationChannels\LaravelZenviaChannel;

class ZenviaConfig
{
    /** @var array */
    private array $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function usingAccountPasswordAuth(): bool
    {
        return $this->getAccount() !== null && $this->getPassword() !== null;
    }

    public function getAccount(): ?string
    {
        return $this->config['account'] ?? null;
    }

    public function getPassword(): ?string
    {
        return $this->config['password'] ?? null;
    }

    public function getFrom(): ?string
    {
        return $this->config['from'] ?? null;
    }
}
