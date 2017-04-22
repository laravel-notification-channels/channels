<?php

namespace NotificationChannels\Smsapi;

use SMSApi\Client;
use SMSApi\Proxy\Proxy;
use SMSApi\Api\MmsFactory;
use SMSApi\Api\SmsFactory;
use SMSApi\Api\VmsFactory;
use SMSApi\Api\Response\Response;

class SmsapiClient
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $defaults;

    /**
     * @var Proxy
     */
    protected $proxy;

    /**
     * @param Client     $client
     * @param array      $defaults
     * @param Proxy|null $proxy
     */
    public function __construct(Client $client, array $defaults = [], Proxy $proxy = null)
    {
        $this->client = $client;
        $this->defaults = $defaults;
        $this->proxy = $proxy;
    }

    /**
     * @param  SmsapiMessage $message
     * @return Response
     */
    public function send(SmsapiMessage $message)
    {
        if ($message instanceof SmsapiSmsMessage) {
            return $this->sendSms($message);
        } elseif ($message instanceof SmsapiMmsMessage) {
            return $this->sendMms($message);
        } elseif ($message instanceof SmsapiVmsMessage) {
            return $this->sendVms($message);
        }
    }

    /**
     * @param  SmsapiSmsMessage $message
     * @return Response
     */
    public function sendSms(SmsapiSmsMessage $message)
    {
        $data = $message->data + $this->defaults;
        $sms = (new SmsFactory($this->proxy, $this->client))->actionSend();
        if (isset($data['content'])) {
            $sms->setText($data['content']);
        }
        if (isset($data['template'])) {
            $sms->setTemplate($data['template']);
        }
        if (isset($data['to'])) {
            $sms->setTo($data['to']);
        }
        if (isset($data['group'])) {
            $sms->setGroup($data['group']);
        }
        if (isset($data['from'])) {
            $sms->setSender($data['from']);
        }
        if (isset($data['fast'])) {
            $sms->setFast($data['fast']);
        }
        if (isset($data['flash'])) {
            $sms->setFlash($data['flash']);
        }
        if (isset($data['encoding'])) {
            $sms->setEncoding($data['encoding']);
        }
        if (isset($data['normalize'])) {
            $sms->setNormalize($data['normalize']);
        }
        if (isset($data['nounicode'])) {
            $sms->setNoUnicode($data['nounicode']);
        }
        if (isset($data['single'])) {
            $sms->setSingle($data['single']);
        }
        if (isset($data['date'])) {
            $sms->setDateSent($data['date']);
        }
        if (isset($data['notify_url'])) {
            $sms->setNotifyUrl($data['notify_url']);
        }
        if (isset($data['partner'])) {
            $sms->setPartner($data['partner']);
        }
        if (isset($data['test'])) {
            $sms->setTest($data['test']);
        }

        return $sms->execute();
    }

    /**
     * @param  SmsapiMmsMessage $message
     * @return Response
     */
    public function sendMms(SmsapiMmsMessage $message)
    {
        $data = $message->data + $this->defaults;
        $mms = (new MmsFactory($this->proxy, $this->client))->actionSend();
        $mms->setSubject($data['subject']);
        $mms->setSmil($data['smil']);
        if (isset($data['to'])) {
            $mms->setTo($data['to']);
        }
        if (isset($data['group'])) {
            $mms->setGroup($data['group']);
        }
        if (isset($data['date'])) {
            $mms->setDateSent($data['date']);
        }
        if (isset($data['notify_url'])) {
            $mms->setNotifyUrl($data['notify_url']);
        }
        if (isset($data['partner'])) {
            $mms->setPartner($data['partner']);
        }
        if (isset($data['test'])) {
            $mms->setTest($data['test']);
        }

        return $mms->execute();
    }

    /**
     * @param  SmsapiVmsMessage $message
     * @return Response
     */
    public function sendVms(SmsapiVmsMessage $message)
    {
        $data = $message->data + $this->defaults;
        $vms = (new VmsFactory($this->proxy, $this->client))->actionSend();
        if (isset($data['file'])) {
            $vms->setFile($data['file']);
        }
        if (isset($data['tts'])) {
            $vms->setTts($data['tts']);
            if (isset($data['tts_lector'])) {
                $vms->setTtsLector($data['tts_lector']);
            }
        }
        if (isset($data['to'])) {
            $vms->setTo($data['to']);
        }
        if (isset($data['group'])) {
            $vms->setGroup($data['group']);
        }
        if (isset($data['from'])) {
            $vms->setFrom($data['from']);
        }
        if (isset($data['tries'])) {
            $vms->setTry($data['tries']);
        }
        if (isset($data['interval'])) {
            $vms->setInterval($data['interval']);
        }
        if (isset($data['date'])) {
            $vms->setDateSent($data['date']);
        }
        if (isset($data['notify_url'])) {
            $vms->setNotifyUrl($data['notify_url']);
        }
        if (isset($data['partner'])) {
            $vms->setPartner($data['partner']);
        }
        if (isset($data['test'])) {
            $vms->setTest($data['test']);
        }

        return $vms->execute();
    }
}
