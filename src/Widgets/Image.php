<?php

namespace NotificationChannels\GoogleChat\Widgets;

class Image extends AbstractWidget
{
    /**
     * Set the image url.
     *
     * @param string $url
     * @return self
     */
    public function imageUrl(string $url): Image
    {
        $this->payload['imageUrl'] = $url;

        return $this;
    }

    /**
     * Make the widget clickable through to the provided link.
     *
     * @param string $url
     * @return self
     */
    public function onClick(string $url): Image
    {
        $this->payload['onClick'] = [
            'openLink' => [
                'url' => $url,
            ],
        ];

        return $this;
    }

    /**
     * Return a new Image widget instance.
     *
     * @param string|null $imageUrl
     * @param string|null $onClickUrl
     * @return self
     */
    public static function create(string $imageUrl = null, string $onClickUrl = null): Image
    {
        $widget = new static;

        if ($imageUrl) {
            $widget->imageUrl($imageUrl);
        }

        if ($onClickUrl) {
            $widget->onClick($onClickUrl);
        }

        return $widget;
    }
}
