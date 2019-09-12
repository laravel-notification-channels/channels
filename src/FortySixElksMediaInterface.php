<?php

namespace NotificationChannels\FortySixElks;

/**
 * Interface FourtSixElksMediaInterface.
 */
interface FortySixElksMediaInterface
{
    /**
     * FourtSixElksMediaInterface constructor.
     */
    public function __construct();

    /**
     * @return mixed
     */
    public function send();
}
