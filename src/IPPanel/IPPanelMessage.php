<?php
namespace NotificationChannels\PersianSms\IPPanel;

class IPPanelMessage
{
    /**
     * The content of the SMS message (for normal SMS).
     *
     * @var string|null
     */
    public ?string $content = null;

    /**
     * The pattern code for pattern-based SMS.
     *
     * @var string|null
     */
    public ?string $patternCode = null;

    /**
     * The variables for the pattern-based SMS.
     * Should be an associative array e.g., ['variableName' => 'value'].
     *
     * @var array<string, mixed>|null
     */
    public ?array $variables = null;

    /**
     * The sender number (line number).
     * If null, the default sender from config will be used.
     *
     * @var string|null
     */
    public ?string $sender = null;

    /**
     * (Optional) The scheduled time for sending the SMS.
     * Format: "YYYY-MM-DDTHH:MM:SSZ" e.g., "2025-03-21T09:12:50.824Z"
     * Note: IPPanel API documentation for single send shows this,
     * but it's not yet implemented in IPPanelChannel.
     *
     * @var string|null
     */
    public ?string $time = null;

    /**
     * The recipient's phone number(s).
     * If set, this will override the number from the notifiable model.
     *
     * @var string|array<string>|null
     */
    public string|array|null $recipient = null;


    /**
     * Create a new message instance for normal SMS.
     *
     * @param string $content The text content of the SMS.
     * @return static
     */
    public static function create(string $content = ''): static
    {
        return new static($content);
    }

    /**
     * Constructor.
     *
     * @param string $content Initial content for a normal SMS.
     */
    public function __construct(string $content = '')
    {
        if (!empty($content)) {
            $this->content($content);
        }
    }

    /**
     * Set the recipient's phone number(s).
     * Overrides the recipient from the notifiable model.
     *
     * @param string|array<string> $recipient A single phone number or an array of phone numbers.
     * @return $this
     */
    public function to(string|array $recipient): self
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * Set the content of the SMS.
     * This is for normal (non-pattern) messages.
     *
     * @param string $content
     * @return $this
     */
    public function content(string $content): self
    {
        $this->content = $content;
        $this->patternCode = null; // Ensure it's not a pattern message if content is set
        $this->variables = null;
        return $this;
    }

    /**
     * Set the message to be sent using a pattern.
     *
     * @param string $patternCode The code of the pattern.
     * @param array<string, mixed> $variables Associative array of variables for the pattern.
     * @return $this
     */
    public function pattern(string $patternCode, array $variables = []): self
    {
        $this->patternCode = $patternCode;
        $this->variables = $variables;
        $this->content = null; // Ensure it's not a normal content message if pattern is set
        return $this;
    }

    /**
     * Set a specific variable for the pattern.
     *
     * @param string $name Name of the variable.
     * @param mixed $value Value of the variable.
     * @return $this
     */
    public function variable(string $name, $value): self
    {
        if ($this->variables === null) {
            $this->variables = [];
        }
        $this->variables[$name] = $value;
        return $this;
    }

    /**
     * Set the sender number (line number) for this message.
     * Overrides the default sender number from the configuration.
     *
     * @param string $senderNumber
     * @return $this
     */
    public function from(string $senderNumber): self
    {
        $this->sender = $senderNumber;
        return $this;
    }

    /**
     * Set the scheduled time for sending the SMS.
     * Note: Ensure IPPanelChannel supports this if you use it.
     *
     * @param string $dateTimeString Format: "YYYY-MM-DDTHH:MM:SSZ"
     * @return $this
     */
    public function at(string $dateTimeString): self
    {
        $this->time = $dateTimeString;
        return $this;
    }

    /**
     * Check if the message is configured to be sent using a pattern.
     *
     * @return bool
     */
    public function isPattern(): bool
    {
        return !empty($this->patternCode);
    }
}
