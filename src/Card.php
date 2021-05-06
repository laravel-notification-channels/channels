<?php

namespace NotificationChannels\GoogleChat;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use NotificationChannels\GoogleChat\Concerns\ValidatesCardComponents;

class Card implements Arrayable
{
    use ValidatesCardComponents;

    /**
     * The card payload.
     *
     * @var array
     */
    protected array $payload = [
        'sections' => [],
    ];

    /**
     * Configure the header content of the card.
     *
     * @param string $title The title of the card, usually the bot or service name
     * @param string|null $subtitle Secondary text displayed below the title
     * @param string|null $imageUrl Display a particular avatar image for the message
     * @param string|null $imageStyle Configure the avatar image style, one of IMAGE or AVATAR
     * @return self
     */
    public function header(string $title, string $subtitle = null, string $imageUrl = null, string $imageStyle = null): Card
    {
        $header = [
            'title' => $title,
        ];

        if ($subtitle) {
            $header['subtitle'] = $subtitle;
        }

        if ($imageUrl) {
            $header['imageUrl'] = $imageUrl;
        }

        if ($imageStyle) {
            $header['imageStyle'] = $imageStyle;
        }

        $this->payload['header'] = $header;

        return $this;
    }

    /**
     * Add one or more sections to the card.
     *
     * @param \NotificationChannels\GoogleChat\Section|\NotificationChannels\GoogleChat\Section[]
     * @return self
     */
    public function section($section): Card
    {
        $sections = Arr::wrap($section);

        $this->guardOnlyInstancesOf(Section::class, $sections);

        $this->payload['sections'] = array_merge($this->payload['sections'] ?? [], $sections);

        return $this;
    }

    /**
     * Serialize the card to an array representation.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->payload;
    }

    /**
     * Return a new Google Chat Card instance.
     *
     * @param \NotificationChannels\GoogleChat\Section|\NotificationChannels\GoogleChat\Section[]|null $section
     * @return self
     */
    public static function create($section = null): Card
    {
        $card = new static;

        if ($section) {
            $card->section($section);
        }

        return $card;
    }
}
