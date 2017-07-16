<?php

namespace NotificationChannels\Orange;

class OrangeMessage
{
    /**
     * @var string
     */
    protected $to;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $text;

    /**
     * @param $to
     * @return \NotificationChannels\Orange\OrangeMessage
     */
    public function to($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @param $from
     * @return \NotificationChannels\Orange\OrangeMessage
     */
    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @param string $text
     * @return \NotificationChannels\Orange\OrangeMessage
     */
    public function text($text = '')
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @param $property
     * @return mixed
     */
    public function __get($property)
    {
        if (property_exists($this, $property) && in_array($property, ['to','from','text'])) {
            return $this->{$property};
        }
    }
}
