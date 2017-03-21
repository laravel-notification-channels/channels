<?php

namespace NotificationChannels\Alidayu;

class AlidayuConfig
{
    /**
     * @var array
     */
    private $config;

    /**
     * AlidayuConfig constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get the app key.
     *
     * @return string
     */
    public function getAppKey()
    {
        return $this->config['app_key'];
    }

    /**
     * Get the app secret.
     *
     * @return string
     */
    public function getAppSecret()
    {
        return $this->config['app_secret'];
    }
}
