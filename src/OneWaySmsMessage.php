<?php

namespace NotificationChannels\OneWaySms;

class OneWaySmsMessage
{
    /** Normal text message: 160 characters per SMS part. */
    public const LANGUAGE_TYPE_TEXT = 1;

    /** Unicode message: 70 characters per SMS part, hex encoded. */
    public const LANGUAGE_TYPE_UNICODE = 2;

    public string $content;

    public ?string $sender = null;

    /** An explicit language type, or null to derive it from the content. */
    protected ?int $languageType = null;

    public function __construct(string $content = '')
    {
        $this->content = $content;
    }

    public static function create(string $content = ''): self
    {
        return new self($content);
    }

    public function content(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function sender(string $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function unicode(): self
    {
        $this->languageType = self::LANGUAGE_TYPE_UNICODE;

        return $this;
    }

    public function text(): self
    {
        $this->languageType = self::LANGUAGE_TYPE_TEXT;

        return $this;
    }

    /**
     * An explicit choice always wins; otherwise any non-ASCII character
     * selects unicode, since the gateway cannot carry it as plain text.
     */
    public function languageType(): int
    {
        if ($this->languageType !== null) {
            return $this->languageType;
        }

        return mb_check_encoding($this->content, 'ASCII')
            ? self::LANGUAGE_TYPE_TEXT
            : self::LANGUAGE_TYPE_UNICODE;
    }

    /**
     * The content as the gateway expects it: raw for text, and UTF-16BE hex
     * for unicode. That yields four hex digits per BMP character and eight for
     * astral characters, which UTF-16 stores as surrogate pairs.
     */
    public function encodedContent(): string
    {
        if ($this->languageType() === self::LANGUAGE_TYPE_TEXT) {
            return $this->content;
        }

        return strtoupper(bin2hex(mb_convert_encoding($this->content, 'UTF-16BE', 'UTF-8')));
    }
}
