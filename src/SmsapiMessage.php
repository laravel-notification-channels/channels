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
    public function to($to)
    {
        $this->data['to'] = $to;

        return $this;
    }

    /**
     * @param  string $group
     * @return self
     */
    public function group($group)
    {
        $this->data['group'] = $group;

        return $this;
    }

    /**
     * @param  string $date
     * @return self
     */
    public function date($date)
    {
        $this->data['date'] = $date;

        return $this;
    }

    /**
     * @param  string $notifyUrl
     * @return self
     */
    public function notifyUrl($notifyUrl)
    {
        $this->data['notify_url'] = $notifyUrl;

        return $this;
    }

    /**
     * @param  string $partner
     * @return self
     */
    public function partner($partner)
    {
        $this->data['partner'] = $partner;

        return $this;
    }

    /**
     * @param  bool $test
     * @return self
     */
    public function test($test)
    {
        $this->data['test'] = $test;

        return $this;
    }
}
