<?php

namespace NotificationChannels\TotalVoice;

class TotalVoiceSmsMessage extends TotalVoiceMessage
{
    /**
     * @var bool
     */
    public $multi_part = false;

    /**
     * @var null|\DateTime
     */
    public $scheduled_datetime = null;

    /**
     * Set the multi-part message option.
     *
     * @param bool $multi_part
     * @return $this
     */
    public function multipart($multi_part)
    {
        $this->multi_part = $multi_part;

        return $this;
    }

    /**
     * Set the scheleduled datetime message option in Datetime Format.
     *
     * @param \DateTime $scheduled_datetime
     * @return $this
     */
    public function scheledule(\DateTime $scheduled_datetime)
    {
        $this->scheduled_datetime = $scheduled_datetime;

        return $this;
    }
}
