<?php

namespace NotificationChannels\FortySixElks;

interface FortySixElksMediaInterface
{
    /**
     * FourtSixElksMediaInterface constructor.
     */
    public function __construct();

    /**
     * @return self
     */
    public function send();
}
