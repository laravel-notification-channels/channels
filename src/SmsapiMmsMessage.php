<?php

namespace NotificationChannels\Smsapi;

class SmsapiMmsMessage extends SmsapiMessage
{
    /**
     * @param  string $subject
     * @return self
     */
    public function subject($subject): self
    {
        $this->data['subject'] = $subject;
        return $this;
    }

    /**
     * @param  string $smil
     * @return self
     */
    public function smil($smil): self
    {
        $this->data['smil'] = $smil;
        return $this;
    }
}
