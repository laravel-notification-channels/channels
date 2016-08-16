<?php

namespace NotificationChannels\Gitter;

use Illuminate\Contracts\Support\Arrayable;

class GitterMessage implements Arrayable
{
    /**
     * Gitter room id.
     *
     * @var string
     */
    public $room = '';

    /**
     * A user or app access token.
     *
     * @var string
     */
    public $from = '';

    /**
     * The text content of the message.
     *
     * @var string
     */
    public $content = '';

    /**
     * Create a new instance of GitterMessage.
     *
     * @param  string  $content
     *
     * @return static
     */
    public static function create($content = '')
    {
        return new static($content);
    }

    /**
     * Create a new instance of GitterMessage.
     *
     * @param $content
     */
    public function __construct($content = '')
    {
        if (! empty($content)) {
            $this->content($content);
        }
    }

    /**
     * Set the Gitter room id to send message to.
     *
     * @param  string  $id
     *
     * @return $this
     */
    public function room($id)
    {
        $this->room = $id;

        return $this;
    }

    /**
     * Set the sender's access token.
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
     * Set the content of the message. Supports Github flavoured markdown.
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

    public function toArray()
    {
        return [
            'room' => $this->room,
            'from' => $this->from,
            'text' => $this->content,
        ];
    }
}
