<?php

namespace NotificationChannels\Alidayu;

class AlidayuMessage
{
    /**
     * The ID of the template.
     *
     * @var string
     */
    public $template;

    /**
     * The array of the parameters.
     *
     * @var array
     */
    public $parameters;

    /**
     * The signature.
     *
     * @var string
     */
    public $signature;

    /**
     * @param string $template
     * @param array  $parameters
     * @param string $signature
     *
     * @return static
     */
    public static function create($template, $parameters = [], $signature = '')
    {
        return new static($template, $parameters, $signature);
    }

    /**
     * @param string $template
     * @param array  $parameters
     * @param string $signature
     */
    public function __construct($template, $parameters = [], $signature = '')
    {
        $this->template   = $template;
        $this->parameters = $parameters;
        $this->signature  = $signature;
    }

    /**
     * Set the template ID of the message.
     *
     * @param string $template
     *
     * @return $this
     */
    public function template($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Set the parameters of the message.
     *
     * @param array $parameters
     *
     * @return $this
     */
    public function parameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Set the signature of the message.
     *
     * @param array $signature
     *
     * @return $this
     */
    public function signature($signature)
    {
        $this->signature = $signature;

        return $this;
    }
}
