<?php

namespace NotificationChannels\Chatwork;

class ChatworkInformation
{
    public $roomId;
    public $informationTitle;
    public $informationMessage;

    public static function create($informationTitle, $informationMessage = '')
    {
        return new static($informationTitle, $informationMessage);
    }

    public function __construct($informationTitle = '', $informationMessage = '')
    {
        $this->informationTitle($informationTitle);
        $this->informationMessage($informationMessage);
    }

    /**
     * Set the message post room id.
     *
     * @param  string  $roomId
     * @return $this
     */
    public function roomId($roomId)
    {
        $this->roomId = $roomId;

        return $this;
    }

    /**
     * Set the information title.
     *
     * @param  string  $informationTitle
     * @return $this
     */
    public function informationTitle($informationTitle)
    {
        $this->informationTitle = $informationTitle;

        return $this;
    }

    /**
     * Set the message information message.
     *
     * @param  string  $informationBody
     * @return $this
     */
    public function informationMessage($informationMessage)
    {
        $this->informationMessage = $informationMessage;

        return $this;
    }
}
