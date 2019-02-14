<?php

namespace NotificationChannels\TotalVoice;

class TotalVoiceAudioMessage extends TotalVoiceMessage
{
    use TotalVoiceMessageOptions;

    /**
     * Create a message object.
     *
     * @param string $audio_url
     * @return static
     */
    public static function create($audio_url = '')
    {
        return new static($audio_url);
    }

    /**
     * Create a new message instance.
     *
     * @param  string $audio_url
     */
    public function __construct($audio_url = '')
    {
        $this->content = $audio_url;
    }
}
