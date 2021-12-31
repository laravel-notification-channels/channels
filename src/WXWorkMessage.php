<?php

declare(strict_types=1);

namespace NotificationChannels\WXWork;

final class WXWorkMessage
{
    /**
     * The POST message of the WXWork request.
     */
    protected string $message;

    public function __construct(string $message = '')
    {
        $this->message = $message;
    }

    /**
     * Get a markdown.
     */
    public static function create(string $message = ''): WXWorkMessage
    {
        return new self($message);
    }

    public function content(string $message = ''): WXWorkMessage
    {
        $this->message = $message;

        return $this;
    }

    public function getContent(): string
    {
        return $this->message;
    }

    /**
     * Get a markdown.
     */
    public function toMarkDown(): string
    {
        return json_encode([
            'msgtype' => 'markdown',
            'markdown' => [
                'content' => $this->message,
            ],
        ]);
    }

    /**
     * Get a text.
     */
    public function toText(): string
    {
        return json_encode([
            'msgtype' => 'text',
            'text' => [
                'content' => $this->message,
            ],
        ]);
    }
}
