<?php

namespace NotificationChannels\Smsapi;

class SmsapiVmsMessage extends SmsapiMessage
{
    /**
     * @param  string $file
     * @return self
     */
    public function file($file): self
    {
        $this->data['file'] = $file;
        return $this;
    }

    /**
     * @param  string $tts
     * @return self
     */
    public function tts($tts): self
    {
        $this->data['tts'] = $tts;
        return $this;
    }

    /**
     * @param  string $ttsLector
     * @return self
     */
    public function ttsLector($ttsLector): self
    {
        $this->data['tts_lector'] = $ttsLector;
        return $this;
    }

    /**
     * @param  string $from
     * @return self
     */
    public function from($from): self
    {
        $this->data['from'] = $from;
        return $this;
    }

    /**
     * @param  int $try
     * @return self
     */
    public function try($try): self
    {
        $this->data['try'] = $try;
        return $this;
    }

    /**
     * @param  int $interval
     * @return self
     */
    public function interval($interval): self
    {
        $this->data['interval'] = $interval;
        return $this;
    }

    /**
     * @param  bool $skipGsm
     * @return self
     */
    public function skipGsm($skipGsm): self
    {
        $this->data['skip_gsm'] = $skipGsm;
        return $this;
    }
}
