<?php

namespace NotificationChannels\TotalVoice;

abstract class TotalVoiceMessage
{
    /**
     * The message content.
     *
     * @var string
     */
    public $content;

    /**
     * Aguardar uma resposta do destinatário.
     *
     * @var bool
     */
    public $provide_feedback = false;

    /**
     * Create a message object.
     *
     * @param string $content
     * @return static
     */
    public static function create($content = '')
    {
        return new static($content);
    }

    /**
     * Create a new message instance.
     *
     * @param  string $content
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * Set the message content.
     *
     * @param string $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the provide feedback option.
     *
     * @param bool $provide_feedback
     * @return $this
     */
    public function provideFeedback($provide_feedback)
    {
        $this->provide_feedback = $provide_feedback;

        return $this;
    }

    /**
     * Get the provide feedback option.
     *
     * @return null|bool
     */
    public function getProvideFeedback()
    {
        return $this->provide_feedback;
    }
}
