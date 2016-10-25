<?php

namespace NotificationChannels\DiscordWebhook;

class DiscordWebhookEmbed
{
    /**
     * The title of embed.
     *
     * @var string
     */
    public $title;

    /**
     * The description of embed.
     *
     * @var string
     */
    public $description;

    /**
     * The URL of embed.
     *
     * @var string
     */
    public $url;

    /**
     * The color code of the embed.
     *
     * @var int
     */
    public $color;

    /**
     * The footer information.
     *
     * @var array
     */
    public $footer;

    /**
     * The image information.
     *
     * @var array
     */
    public $image;

    /**
     * The thumbnail information.
     *
     * @var array
     */
    public $thumbnail;

    /**
     * The author information.
     *
     * @var array
     */
    public $author;

    /**
     * The fields information.
     *
     * @var array
     */
    public $fields;

    /**
     * Set the title (url) of embed.
     *
     * @param string $title
     * @param string|null $url
     *
     * @return $this
     */
    public function title($title, $url = '')
    {
        $this->title = $title;
        $this->url = $url;

        return $this;
    }

    /**
     * Set the description (text) of embed.
     *
     * @param string $description
     *
     * @return $this
     */
    public function description($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set the color code of the embed.
     *
     * @param int $code
     *
     * @return $this
     */
    public function color($code)
    {
        $this->color = $code;

        return $this;
    }

    /**
     * Set the footer information.
     *
     * @param string $text
     * @param string|null $icon_url
     *
     * @return $this
     */
    public function footer($text, $icon_url = '')
    {
        $this->footer = [
            'text' => $text,
            'icon_url' => $icon_url,
        ];

        return $this;
    }

    /**
     * Set the image (url) information.
     *
     * @param string $url
     *
     * @return $this
     */
    public function image($url)
    {
        $this->image = [
            'url' => $url,
        ];

        return $this;
    }

    /**
     * Set the thumbnail (url) information.
     *
     * @param string $url
     *
     * @return $this
     */
    public function thumbnail($url)
    {
        $this->thumbnail = [
            'url' => $url,
        ];

        return $this;
    }

    /**
     * Set the author information.
     *
     * @param string $name
     * @param string|null $url
     * @param string|null $icon_url
     *
     * @return $this
     */
    public function author($name, $url = '', $icon_url = '')
    {
        $this->author = [
            'name' => $name,
            'url' => $url,
            'icon_url' => $icon_url,
        ];

        return $this;
    }

    /**
     * Set the fields information.
     *
     * @param string $name
     * @param string $value
     * @param bool|null $inline
     *
     * @return $this
     */
    public function field($name, $value, $inline = false)
    {
        $this->fields[] = new DiscordWebhookEmbedField($name, $value, $inline);

        return $this;
    }

    /**
     * Get an array representation of the embedded content.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'color' => $this->color,
            'footer' => $this->footer,
            'image' => $this->image,
            'thumbnail' => $this->thumbnail,
            'author' => $this->author,
            'fields' => $this->fields,
        ];
    }
}
