<?php

namespace NotificationChannels\SmsCentre;

use Illuminate\Contracts\Support\Arrayable;

class SmsCentreMessage implements Arrayable
{
    /**
     * The phone number the message should be sent from.
     *
     * @var string
     */
    public $from;

    /**
     * The message content.
     *
     * @var string
     */
    public $content;

    /**
     * Create a new message instance.
     *
     * @param  string  $content
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * Set the message content.
     *
     * @param  string  $content
     *
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the phone number or sender name the message should be sent from.
     *
     * @param  string  $from
     *
     * @return $this
     */
    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $params = [
            'mes' => $this->content,
            'charset' => 'utf-8'
        ];

        if (! empty($this->from)) {
            $params = array_merge($params, ['sender' => $this->from]);
        }

        return $params;
    }
}
