<?php

namespace NotificationChannels\Sailthru;

class SailthruMessage
{
    /**
     * @var string
     */
    protected $template;

    /**
     * The message parameter variables
     *
     * @var array
     */
    protected $vars = [];

    /**
     * Multi-send EmailVars
     *
     * @var array
     */
    protected $eVars = [];

    /**
     * The email address the message should be sent from.
     *
     * @var string
     */
    protected $fromEmail;

    /**
     * The From Name the message should be sent from.
     *
     * @var string
     */
    protected $fromName;

    /**
     * The email address for the Recipient
     *
     * @var string
     */
    protected $toEmail;

    /**
     * The Name of the Recipient
     *
     * @var string
     */
    protected $toName;

    /**
     * The Reply To for the message
     *
     * @var string
     */
    protected $replyTo;

    /**
     * @var bool
     */
    protected $isMultiSend = false;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * SailthruMessage constructor.
     *
     * @param string $template
     */
    public function __construct(string $template)
    {
        $this->template = $template;
        $this->fromEmail = config('mail.from.address');
        $this->fromName = config('mail.from.name');
    }

    /**
     * @param string $template
     *
     * @return SailthruMessage
     */
    public static function create(string $template): SailthruMessage
    {
        return new static($template);
    }

    /**
     * @param array $vars
     *
     * @return SailthruMessage
     */
    public function vars(array $vars): SailthruMessage
    {
        $this->vars = $vars;

        return $this;
    }

    /**
     * @param array $eVars
     *
     * @return SailthruMessage
     */
    public function eVars(array $eVars): SailthruMessage
    {
        $this->eVars = $eVars;

        return $this;
    }

    /**
     * @param array $defaultVars
     *
     * @return SailthruMessage
     */
    public function mergeDefaultVars(array $defaultVars): SailthruMessage
    {
        $this->vars = array_merge($defaultVars, $this->getVars());

        return $this;
    }

    /**
     * @param string $template
     *
     * @return $this
     */
    public function template(string $template): SailthruMessage
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @param string $toName
     *
     * @return SailthruMessage
     */
    public function toName(string $toName): SailthruMessage
    {
        $this->toName = title_case($toName);

        return $this;
    }

    /**
     * @param string $fromName
     *
     * @return SailthruMessage
     */
    public function fromName(string $fromName): SailthruMessage
    {
        $this->fromName = $fromName;

        return $this;
    }

    /**
     * @param string $toEmail
     *
     * @return SailthruMessage
     */
    public function toEmail(string $toEmail): SailthruMessage
    {
        $this->toEmail = $toEmail;

        return $this;
    }

    /**
     * @param string $toEmail
     *
     * @return SailthruMessage
     */
    public function toEmails(array $toEmails): SailthruMessage
    {
        $this->toEmail = implode(',', $toEmails);
        $this->isMultiSend = true;

        return $this;
    }

    /**
     * @param string $fromEmail
     *
     * @return SailthruMessage
     */
    public function fromEmail(string $fromEmail): SailthruMessage
    {
        $this->fromEmail = $fromEmail;

        return $this;
    }

    /**
     * @param string $replyTo
     *
     * @return SailthruMessage
     */
    public function replyTo(string $replyTo): SailthruMessage
    {
        $this->replyTo = $replyTo;

        return $this;
    }

    /**
     * @param array $options
     *
     * @return SailthruMessage
     */
    public function options(array $options): SailthruMessage
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * @return array
     */
    public function getEVars()
    {
        return $this->eVars;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $options = $this->options;

        if ($this->replyTo) {
            $options['replyto'] = $this->replyTo;
        }

        return $options;
    }

    /**
     * @return string
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * @return string
     */
    public function getToEmail()
    {
        return $this->toEmail;
    }

    /**
     * @return string
     */
    public function getToName()
    {
        return $this->toName;
    }

    /**
     * @return string
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * @return bool
     */
    public function isMultiSend(): bool
    {
        return $this->isMultiSend;
    }
}
