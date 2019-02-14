<?php

namespace NotificationChannels\TotalVoice;

class TotalVoiceConfig
{
    /**
     * @var array
     */
    private $config;

    /**
     * TotalVoiceConfig constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get the access token.
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->config['access_token'];
    }
}
