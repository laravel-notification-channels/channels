<?php

namespace NotificationChannels\DiscordWebhook;

class DiscordWebhookMessage
{
    /**
     * The message contents (up to 2000 characters).
     *
     * @var string
     */
    public $content;

    /**
     * Override the default username of the webhook.
     *
     * @var string|null
     */
    public $username;

    /**
     * Override the default avatar of the webhook.
     *
     * @var string|null
     */
    public $avatar_url;

    /**
     * true if this is a TTS message.
     *
     * @var string|null
     */
    public $tts = 'false';

    /**
     * The contents of the file being sent.
     *
     * @var array
     */
    public $file;

    /**
     * Embedded rich content.
     *
     * @var array
     */
    public $embeds;

    /**
     * Allows to set the content by creation.
     *
     * @param string $content
     */
    public function __construct($content = null)
    {
        if (! is_null($content)) {
            $this->content($content);
        }
    }

    /**
     * Set the content of the message.
     *
     * @param string $content
     *
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Override the default username and avatar url of the webhook.
     *
     * @param string $username
     * @param string|null $avatar_url
     *
     * @return $this
     */
    public function from($username, $avatar_url = null)
    {
        $this->username = $username;

        if (! is_null($avatar_url)) {
            $this->avatar_url = $avatar_url;
        }

        return $this;
    }

    /**
     * Send as a TTS message.
     *
     * @param bool|null $enabled
     *
     * @return $this
     */
    public function tts($enabled = true)
    {
        $this->tts = $enabled ? 'true' : 'false';

        return $this;
    }

    /**
     * Set the contents and filename of the file being sent.
     *
     * @param string $contents
     * @param string $filename
     *
     * @return $this
     */
    public function file($contents, $filename)
    {
        $this->file = [
            'name' => 'file',
            'contents' => $contents,
            'filename' => $filename,
        ];

        return $this;
    }

    /**
     * Define an embedded rich content for the message.
     *
     * @param \Closure $callback
     *
     * @return $this
     */
    public function embed(\Closure $callback)
    {
        $this->embeds[] = $embed = new DiscordWebhookEmbed;

        $callback($embed);

        return $this;
    }

    /**
     * Get an array representation of the message.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'content' => $this->content,
            'username' => $this->username,
            'avatar_url' => $this->avatar_url,
            'tts' => $this->tts,
            'file' => $this->file,
            'embeds' => $this->embeds,
        ];
    }
}
