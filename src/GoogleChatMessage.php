<?php

namespace NotificationChannels\GoogleChat;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use NotificationChannels\GoogleChat\Concerns\ValidatesCardComponents;

class GoogleChatMessage implements Arrayable
{
    use ValidatesCardComponents;

    /**
     * The configured message payload.
     *
     * @var array
     */
    protected array $payload = [];

    /**
     * The Space's webhook URL where this message should be sent.
     *
     * @var string|null
     */
    protected ?string $endpoint = null;

    /**
     * Set a specific space's webhook URL where this message should be sent to.
     *
     * @param string $space Either a fully-qualified URL, or a nested configuration key
     * @return self
     */
    public function to(string $space): GoogleChatMessage
    {
        $this->endpoint = $space;

        return $this;
    }

    /**
     * Append text content as a simple text message.
     *
     * @param string $message
     * @return self
     */
    public function text(string $message): GoogleChatMessage
    {
        $this->payload['text'] = ($this->payload['text'] ?? '').$message;

        return $this;
    }

    /**
     * Append simple text content on a new line.
     *
     * @param string $message
     * @return self
     */
    public function line(string $message): GoogleChatMessage
    {
        $this->text("\n".$message);

        return $this;
    }

    /**
     * Append bold text.
     *
     * @param string $message
     * @return self
     */
    public function bold(string $message): GoogleChatMessage
    {
        $this->text("*{$message}*");

        return $this;
    }

    /**
     * Append italic text.
     *
     * @param string $message
     * @return self
     */
    public function italic(string $message): GoogleChatMessage
    {
        $this->text("_{$message}_");

        return $this;
    }

    /**
     * Append strikethrough text.
     *
     * @param string $message
     * @return self
     */
    public function strikethrough(string $message): GoogleChatMessage
    {
        $this->text("~{$message}~");

        return $this;
    }

    /**
     * Append strikethrough text.
     *
     * @param string $message
     * @return self
     */
    public function strike(string $message): GoogleChatMessage
    {
        return $this->strikethrough($message);
    }

    /**
     * Append monospace text.
     *
     * @param string $message
     * @return self
     */
    public function monospace(string $message): GoogleChatMessage
    {
        $this->text("`{$message}`");

        return $this;
    }

    /**
     * Append monospace text.
     *
     * @param string $message
     * @return self
     */
    public function mono(string $message): GoogleChatMessage
    {
        return $this->monospace($message);
    }

    /**
     * Append monospace block text.
     *
     * @param string $message
     * @return self
     */
    public function monospaceBlock(string $message): GoogleChatMessage
    {
        $this->text("```{$message}```");

        return $this;
    }

    /**
     * Append a text link.
     *
     * @param string $link
     * @param string|null $displayText
     * @return self
     */
    public function link(string $link, string $displayText = null): GoogleChatMessage
    {
        if ($displayText) {
            $link = "<{$link}|{$displayText}>";
        }

        $this->text($link);

        return $this;
    }

    /**
     * Append mention text.
     *
     * @param string $userId
     * @return self
     */
    public function mention(string $userId): GoogleChatMessage
    {
        $this->text("<users/{$userId}>");

        return $this;
    }

    /**
     * Append mention-all text.
     *
     * @param string|null $prependText
     * @param string|null $appendText
     * @return self
     */
    public function mentionAll(string $prependText = null, string $appendText = null): GoogleChatMessage
    {
        $this->text("{$prependText}<users/all>{$appendText}");

        return $this;
    }

    /**
     * Add a one or more cards to the message.
     *
     * @param \NotificationChannels\GoogleChat\Card|\NotificationChannels\GoogleChat\Card[] $card
     * @return self
     */
    public function card($card): GoogleChatMessage
    {
        $cards = Arr::wrap($card);

        $this->guardOnlyInstancesOf(Card::class, $cards);

        $this->payload['cards'] = array_merge($this->payload['cards'] ?? [], $cards);

        return $this;
    }

    /**
     * Return the configured webhook URL of the recipient space, or null if this has
     * not been configured.
     *
     * @return string|null
     */
    public function getSpace(): ?string
    {
        return $this->endpoint;
    }

    /**
     * Serialize the message to an array representation.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->castNestedArrayables($this->payload);
    }

    /**
     * Recursively attempt to cast arrayable values within an array to their
     * primitive representation.
     *
     * @param mixed $value
     * @return mixed
     */
    private function castNestedArrayables($value)
    {
        if ($value instanceof Arrayable) {
            $value = $value->toArray();
        }

        if (is_array($value)) {
            foreach ($value as $key => $val) {
                $value[$key] = $this->castNestedArrayables($val);
            }
        }

        return $value;
    }

    /**
     * Return a new Google Chat Message instance. Optionally, configure it as a simple
     * text message using the provided message string.
     *
     * @param string|null $text
     * @return self
     */
    public static function create(string $text = null): GoogleChatMessage
    {
        $message = new static;

        if ($text) {
            $message->text($text);
        }

        return $message;
    }
}
