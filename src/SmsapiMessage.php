<?php

namespace NotificationChannels\Smsapi;

abstract class SmsapiMessage
{
    /**
     * @internal
     * @var array
     */
    public $data = [];

    /**
     * @param  string|string[] $to
     * @return self
     */
    public function to($to): self
    {
        $this->data['to'] = $to;
        return $this;
    }

    /**
     * @param  string $group
     * @return self
     */
    public function group($group): self
    {
        $this->data['group'] = $group;
        return $this;
    }

    /**
     * @param  string $date
     * @return self
     */
    public function date($date): self
    {
        $this->data['date'] = $date;
        return $this;
    }

    /**
     * @param  string $from
     * @return self
     */
    public function notifyUrl($notifyUrl): self
    {
        $this->data['notify_url'] = $notifyUrl;
        return $this;
    }

    /**
     * @param  string $partner
     * @return self
     */
    public function partner($partner): self
    {
        $this->data['partner'] = $partner;
        return $this;
    }

    /**
     * @param  bool $test
     * @return self
     */
    public function test($test): self
    {
        $this->data['test'] = $test;
        return $this;
    }
}
