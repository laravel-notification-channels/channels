<?php

namespace NotificationChannels\AwsSns;

use Aws\Sns\SnsClient as SnsService;

class Sns
{
    /**
     * @var SnsService
     */
    protected $snsService;

    /**
     * Sns constructor.
     * @param  SnsService  $snsService
     */
    public function __construct(SnsService $snsService)
    {
        $this->snsService = $snsService;
    }
}
