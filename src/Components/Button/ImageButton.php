<?php

namespace NotificationChannels\GoogleChat\Components\Button;

class ImageButton extends AbstractButton
{
    /**
     * Set the button icon.
     *
     * @param string $icon
     * @return self
     */
    public function icon(string $icon): ImageButton
    {
        strpos($icon, '://') === false
            ? $this->setIconByName($icon)
            : $this->setIconByUrl($icon);

        return $this;
    }

    /**
     * Set an icon by its name.
     *
     * @param string $icon
     * @return self
     */
    public function setIconByName(string $icon): ImageButton
    {
        $this->payload['icon'] = $icon;
        unset($this->payload['iconUrl']);

        return $this;
    }

    /**
     * Set an icon by url.
     *
     * @param string $url
     * @return self
     */
    public function setIconByUrl(string $url): ImageButton
    {
        $this->payload['iconUrl'] = $url;
        unset($this->payload['icon']);

        return $this;
    }

    /**
     * Create a new image button instance.
     *
     * @param string|null $url
     * @param string|null $icon Either an icon name or URL to the icon image
     * @return self
     */
    public static function create(string $url = null, string $icon = null): ImageButton
    {
        $button = new static;

        if ($url) {
            $button->url($url);
        }

        if ($icon) {
            $button->icon($icon);
        }

        return $button;
    }
}
