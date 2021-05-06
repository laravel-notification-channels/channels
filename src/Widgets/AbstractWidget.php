<?php

namespace NotificationChannels\GoogleChat\Widgets;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

abstract class AbstractWidget implements Arrayable
{
    /**
     * The widget payload.
     *
     * @var array
     */
    protected array $payload = [];

    /**
     * Serialize the widget to an array representation.
     *
     * @return array
     */
    public function toArray()
    {
        $widgetName = Str::of(
            Str::of(get_called_class())
                ->explode('\\')
                ->last()
        )
        ->camel();

        return [
            (string) $widgetName => $this->payload,
        ];
    }
}
