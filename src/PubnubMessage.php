<?php

namespace NotificationChannels\Pubnub;

use Illuminate\Support\Arr;

class PubnubMessage
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
     * If the message should be stored in the Pubnub history
     *
     * @var bool
     */
    public $storeInHistory = true;

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
     * @param   string|array  $content
     * @return  $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the option to store the current message in the Pubnub history
     *
     * @param   bool    $shouldStore
     * @return  $this
     */
    public function storeInHistory($shouldStore = true)
    {
        $this->storeInHistory = (bool) $shouldStore;

        return $this;
    }
}
