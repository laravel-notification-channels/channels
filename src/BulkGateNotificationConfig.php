<?php

namespace NotificationChannels\BulkGate;

use BulkGate\Sms\SenderSettings\Gate;
use NotificationChannels\BulkGate\Exceptions\InvalidConfigException;

class BulkGateNotificationConfig
{
    /**
     * @var array
     */
    protected $settings;

    /**
     * @throws \NotificationChannels\BulkGate\Exceptions\InvalidConfigException
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
        $this->validateConfiguration();
    }

    public function getSendUnicode(): bool
    {
        return $this->settings['send_unicode'];
    }

    public function getAppId(): int
    {
        return (int) $this->settings['app_id'];
    }

    public function getAppToken(): string
    {
        return (string) $this->settings['app_token'];
    }

    public function getDefaultCountry(): string
    {
        return $this->settings['default_country'];
    }

    public function getSenderType(): string
    {
        return $this->settings['sender_type'];
    }

    public function getSenderId(): string
    {
        return $this->settings['sender_id'];
    }

    /**
     * @throws \NotificationChannels\BulkGate\Exceptions\InvalidConfigException
     */
    protected function validateConfiguration()
    {
        if (! isset($this->settings['app_id']) || ! is_int((int) $this->settings['app_id'])) {
            throw InvalidConfigException::invalidConfiguration('BulkGate app id is missing or invalid.');
        }

        if (! isset($this->settings['app_token']) || ! is_string($this->settings['app_token'])) {
            throw InvalidConfigException::invalidConfiguration('BulkGate app token is missing or invalid.');
        }

        if (in_array($this->settings['sender_type'], [Gate::GATE_OWN_NUMBER, Gate::GATE_TEXT_SENDER], true)
            && empty($this->getSenderId())
        ) {
            throw InvalidConfigException::invalidConfiguration('Sender ID is required for this type of sender. Set sender_id in configuration file or use another sender type.');
        }

        if (Gate::GATE_TEXT_SENDER === $this->settings['sender_type']
            && strlen($this->settings['sender_id']) > 11) {
            throw InvalidConfigException::invalidConfiguration('Sender ID must be 11 characters or less for this sender type.');
        }
    }
}
