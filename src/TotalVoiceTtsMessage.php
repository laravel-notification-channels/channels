<?php

namespace NotificationChannels\TotalVoice;

class TotalVoiceTtsMessage extends TotalVoiceMessage
{
    use TotalVoiceMessageOptions;

    /**
     * @var int
     */
    public $speed = 0;

    /**
     * @var string
     */
    public $voice_type = 'br-Vitoria';

    /**
     * Set the speech speed option. (-10 ~ 10, 0=default).
     *
     * @param int $speed
     * @return $this
     */
    public function speed($speed)
    {
        $this->speed = $speed;

        return $this;
    }

    /**
     * Set the voice type of the message. (br-Vitoria default).
     *
     * @param string $voice_type
     * @return $this
     */
    public function voiceType($voice_type)
    {
        $this->voice_type = $voice_type;

        return $this;
    }
}
