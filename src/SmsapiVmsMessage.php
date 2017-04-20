<?php

namespace NotificationChannels\Smsapi;

class SmsapiVmsMessage extends SmsapiMessage
{
    /**
     * @param  string $file
     * @return self
     */
    public function file($file)
    {
        $this->data['file'] = $file;
        return $this;
    }

    /**
     * @param  string $tts
     * @return self
     */
    public function tts($tts)
    {
        $this->data['tts'] = $tts;
        return $this;
    }

    /**
     * @param  string $ttsLector
     * @return self
     */
    public function ttsLector($ttsLector)
    {
        $this->data['tts_lector'] = $ttsLector;
        return $this;
    }

    /**
     * @param  string $from
     * @return self
     */
    public function from($from)
    {
        $this->data['from'] = $from;
        return $this;
    }

    /**
     * @param  int $try
     * @return self
     */
    public function try($try)
    {
        $this->data['try'] = $try;
        return $this;
    }

    /**
     * @param  int $interval
     * @return self
     */
    public function interval($interval)
    {
        $this->data['interval'] = $interval;
        return $this;
    }

    /**
     * @param  bool $skipGsm
     * @return self
     */
    public function skipGsm($skipGsm)
    {
        $this->data['skip_gsm'] = $skipGsm;
        return $this;
    }
}
