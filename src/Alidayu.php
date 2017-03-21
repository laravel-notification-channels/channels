<?php

namespace NotificationChannels\Alidayu;

use Flc\Alidayu\App as AlidayuApplication;
use Flc\Alidayu\Client as AlidayuService;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend as AlidayuRequest;
use NotificationChannels\Alidayu\Exceptions\CouldNotSendNotification;

class Alidayu
{
    /**
     * Alidayu API client.
     *
     * @var AlidayuService
     */
    protected $alidayuService;

    /**
     * Alidayu constructor.
     *
     * @param AlidayuService  $alidayuService
     * @param AlidayuConfig   $config
     */
    public function __construct(AlidayuConfig $config)
    {
        $this->alidayuService = new AlidayuService(
            new AlidayuApplication([
                'app_key'    => $config->getAppKey(),
                'app_secret' => $config->getAppSecret(),
            ])
        );
    }

    /**
     * Send a AlidayuMessage to a phone number.
     *
     * @param  AlidayuMessage $message
     * @param  string         $to
     * @return mixed
     * @throws CouldNotSendNotification
     */
    public function send(AlidayuMessage $message, $to)
    {
        $req = new AlidayuRequest();

        $req->setRecNum($to)
            ->setSmsParam($message->parameters)
            ->setSmsFreeSignName($message->signature)
            ->setSmsTemplateCode($message->template);

        return $this->alidayuService->execute($req);
    }
}
