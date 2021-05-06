<?php

namespace NotificationChannels\GoogleChat\Widgets;

class TextParagraph extends AbstractWidget
{
    /**
     * Append text content to the widget.
     *
     * @param string $message
     * @return self
     */
    public function text(string $message): TextParagraph
    {
        $this->payload['text'] = ($this->payload['text'] ?? '').$message;

        return $this;
    }

    /**
     * Append bold text context.
     *
     * @param string $message
     * @return self
     */
    public function bold(string $message): TextParagraph
    {
        return $this->text("<b>{$message}</b>");
    }

    /**
     * Append italic text context.
     *
     * @param string $message
     * @return self
     */
    public function italic(string $message): TextParagraph
    {
        return $this->text("<i>{$message}</i>");
    }

    /**
     * Append underline text context.
     *
     * @param string $message
     * @return self
     */
    public function underline(string $message): TextParagraph
    {
        return $this->text("<u>{$message}</u>");
    }

    /**
     * Append strikethrough text context.
     *
     * @param string $message
     * @return self
     */
    public function strikethrough(string $message): TextParagraph
    {
        return $this->text("<strike>{$message}</strike>");
    }

    /**
     * Append strikethrough text context.
     *
     * @param string $message
     * @return self
     */
    public function strike(string $message): TextParagraph
    {
        return $this->strikethrough($message);
    }

    /**
     * Append colored text context.
     *
     * @param string $message
     * @param string $hex
     * @return self
     */
    public function color(string $message, string $hex): TextParagraph
    {
        return $this->text("<font color=\"{$hex}\">{$message}</font>");
    }

    /**
     * Append a text link.
     *
     * @param string $link
     * @param string|null $displayText
     * @return self
     */
    public function link(string $link, string $displayText = null): TextParagraph
    {
        return $this->text("<a href=\"{$link}\">".($displayText ?? $link).'</a>');
    }

    /**
     * Append a line break.
     *
     * @return self
     */
    public function break(): TextParagraph
    {
        return $this->text('<br>');
    }

    /**
     * Return a new Text Paragraph widget instance.
     *
     * @param string|null $message
     * @return self
     */
    public static function create(string $message = null): TextParagraph
    {
        $widget = new static;

        if ($message) {
            $widget->text($message);
        }

        return $widget;
    }
}
