<?php

namespace NotificationChannels\PagerDuty;

use Illuminate\Support\Arr;

class PagerDutyMessage
{
    const EVENT_TRIGGER = 'trigger';
    const EVENT_RESOLVE = 'resolve';

    protected $payload = [];
    protected $meta = [];

    public function __construct()
    {
        Arr::set($this->meta, 'event_action', self::EVENT_TRIGGER);

        Arr::set($this->payload, 'source', gethostname());
        Arr::set($this->payload, 'severity', 'critical');
    }

    public function routingKey($value)
    {
        return $this->setMeta('routing_key', $value);
    }

    public function resolve()
    {
        return $this->setMeta('event_action', self::EVENT_RESOLVE);
    }

    public function dedupKey($key)
    {
        return $this->setMeta('dedup_key', $key);
    }

    public function summary($value)
    {
        return $this->setPayload('summary', $value);
    }

    public function source($value)
    {
        return $this->setPayload('source', $value);
    }

    public function severity($value)
    {
        return $this->setPayload('severity', $value);
    }

    public function timestamp($value)
    {
        return $this->setPayload('timestamp', $value);
    }

    public function component($value)
    {
        return $this->setPayload('component', $value);
    }

    public function group($value)
    {
        return $this->setPayload('group', $value);
    }

    public function setClass($value)
    {
        return $this->setPayload('class', $value);
    }

    public function addCustomDetail($key, $value)
    {
        return $this->setPayload("custom_details.$key", $value);
    }

    protected function setPayload($key, $value)
    {
        Arr::set($this->payload, $key, $value);
        return $this;
    }

    protected function setMeta($key, $value)
    {
        Arr::set($this->meta, $key, $value);
        return $this;
    }

    public function toArray() {
        return Arr::collapse([$this->meta, ['payload'=>$this->payload]]);
    }
}
