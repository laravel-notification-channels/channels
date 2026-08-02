<?php

namespace NotificationChannels\SmsIr;

use DateTimeInterface;

class SmsIrMessage
{
    /**
     * The message content, for a plain-text send.
     *
     * @var string
     */
    public $content = '';

    /**
     * The template id, for a verification send.
     *
     * Either a numeric id from the sms.ir panel, or the name of an entry under
     * `smsir.templates`.
     *
     * @var int|string|null
     */
    public $template;

    /**
     * Template parameters, keyed by the placeholder name.
     *
     * A placeholder written `#CODE#` in the panel is sent as `CODE` here.
     *
     * @var array<string, string|int>
     */
    public $parameters = [];

    /**
     * The line number the message should be sent from.
     *
     * @var int|string|null
     */
    public $from;

    /**
     * The time the message should be sent.
     *
     * @var DateTimeInterface|null
     */
    public $sendAt;

    /**
     * @param  string  $content
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * Create a plain-text message.
     *
     * @param  string  $content
     * @return static
     */
    public static function create($content = '')
    {
        return new static($content);
    }

    /**
     * Create a verification (template) message.
     *
     * One-time passwords must be sent this way: sms.ir requires them to go out
     * on a template line rather than as free text.
     *
     * @param  int|string  $template
     * @return static
     */
    public static function verify($template)
    {
        $message = new static();
        $message->template = $template;

        return $message;
    }

    /**
     * Set the message content.
     *
     * @param  string  $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the template parameters.
     *
     * @param  array<string, string|int>  $parameters
     * @return $this
     */
    public function with(array $parameters)
    {
        $this->parameters = array_merge($this->parameters, $parameters);

        return $this;
    }

    /**
     * Set the line number the message should be sent from.
     *
     * @param  int|string  $from
     * @return $this
     */
    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Set the time the message should be sent.
     *
     * @return $this
     */
    public function sendAt(?DateTimeInterface $sendAt = null)
    {
        $this->sendAt = $sendAt;

        return $this;
    }

    /**
     * Whether this message goes out through the verification endpoint.
     *
     * @return bool
     */
    public function isVerification()
    {
        return $this->template !== null;
    }
}
