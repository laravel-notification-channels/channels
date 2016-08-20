<?php

namespace NotificationChannels\Gammu;

use Illuminate\Support\Arr;

class GammuMessage
{
    const VERSION = '1.0';
    
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
        $this->payload['CreatorID'] = class_basename($this).'/'.self::VERSION;
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
    public function sender($phoneId = null)
    {
        $this->payload['SenderID'] = $phoneId;
        return $this;
    }
    
    /**
     * Determine if Sender Phone ID is not given.
     *
     * @return bool
     */
    public function senderNotGiven()
    {
        return !isset($this->payload['SenderID']);
    }
    
    /**
     * Determine if Destination Phone Number is not given.
     *
     * @return bool
     */
    public function destinationNotGiven()
    {
        return !isset($this->payload['DestinationNumber']);
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
