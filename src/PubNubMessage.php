<?php

namespace NotificationChannels\PubNub;

use Illuminate\Support\Arr;

class PubNubMessage
{
    /**
     * Channel the message should be sent to
     *
     * @var string
     */
    public $channel;

    /**
     * Content of the message
     *
     * @var string
     */
    public $content;

    /**
     * Set the channel the message should be sent to
     *
     * @param   string  $channel
     * @return  $this
     */
    public function channel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Set the content the message should contain
     *
     * @param   string  $content
     * @return  $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }
}
