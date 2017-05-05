<?php

namespace NotificationChannels\PagerDuty;

use Illuminate\Support\Arr;

class PagerDutyMessage
{
    const EVENT_TRIGGER = 'trigger';
    const EVENT_RESOLVE = 'resolve';

    protected $payload = [];

    public function __construct()
    {
        Arr::set($this->payload, 'event_action', self::EVENT_TRIGGER);

        Arr::set($this->payload, 'payload.source', gethostname());
        Arr::set($this->payload, 'payload.severity', 'critical');
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function routingKey($value)
    {
        return $this->setOnBody('routing_key', $value);
    }

    public function resolve()
    {
        return $this->setOnBody('event_action', self::EVENT_RESOLVE);
    }

    public function dedupKey($key)
    {
        return $this->setOnBody('dedup_key', $key);
    }

    public function summary($value)
    {
        return $this->setOnBody('payload.summary', $value);
    }

    public function source($value)
    {
        return $this->setOnBody('payload.source', $value);
    }

    public function severity($value)
    {
        return $this->setOnBody('payload.severity', $value);
    }

    public function timestamp($value)
    {
        return $this->setOnBody('payload.timestamp', $value);
    }

    public function component($value)
    {
        return $this->setOnBody('payload.component', $value);
    }

    public function group($value)
    {
        return $this->setOnBody('payload.group', $value);
    }

    public function setClass($value)
    {
        return $this->setOnBody('payload.class', $value);
    }

    public function addCustomDetail($key, $value)
    {
        return $this->setOnBody("payload.custom_details.$key", $value);
    }

    protected function setOnBody($key, $value)
    {
        Arr::set($this->payload, $key, $value);
        return $this;
    }
}
