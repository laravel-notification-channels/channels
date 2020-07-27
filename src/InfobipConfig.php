<?php

namespace NotificationChannels\Infobip;

class InfobipConfig
{
    public $config;

    /**
     * InfobipConfig constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->config['from'];
    }

    /**
     * @return mixed
     */
    public function getNotifyUrl()
    {
        return $this->config['notify_url'];
    }
}
