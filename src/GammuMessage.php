<?php

namespace NotificationChannels\Gammu;

use Illuminate\Support\Arr;

class GammuMessage
{
    /**
     * @var array Params payload.
     */
    public $payload = [];
    
    /**
     * @param string $content
     *
     * @return static
     */
    public static function create($content = '')
    {
        return new static($content);
    }
        
    /**
     * Create a new message instance.
     *
     * @param  string  $content
     */
    public function __construct($content = '')
    {
        $this->content($content);
        $this->payload['CreatorID'] = 'laravel-notification-channels/gammu';
    }
    
    /**
     * Destination phone number.
     *
     * @param $phoneNumber
     *
     * @return $this
     */
    public function to($phoneNumber)
    {
        $this->payload['DestinationNumber'] = $phoneNumber;
        return $this;
    }
    
    /**
     * SMS message.
     *
     * @param $content
     *
     * @return $this
     */
    public function content($content)
    {
        $this->payload['TextDecoded'] = $content;
        return $this;
    }
    
    /**
     * Sender Phone ID.
     *
     * @param $phoneId
     *
     * @return $this
     */
    public function sender($phoneId)
    {
        $this->payload['SenderID'] = $phoneId;
        return $this;
    }
    
    /**
     * Returns params payload.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->payload;
    }
}
