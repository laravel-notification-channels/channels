<?php

namespace NotificationChannels\GoogleChat\Widgets;

use Illuminate\Support\Arr;
use NotificationChannels\GoogleChat\Components\Button\AbstractButton;
use NotificationChannels\GoogleChat\Concerns\ValidatesCardComponents;

class Buttons extends AbstractWidget
{
    use ValidatesCardComponents;

    /**
     * Add one or more buttons.
     *
     * @param \NotificationChannels\GoogleChat\Components\Button\AbstractButton|\NotificationChannels\GoogleChat\Components\Button\AbstractButton[] $button
     * @return self
     */
    public function button($button): Buttons
    {
        $buttons = Arr::wrap($button);

        $this->guardOnlyInstancesOf(AbstractButton::class, $buttons);

        $this->payload = array_merge($this->payload, $buttons);

        return $this;
    }

    /**
     * Return a new Buttons widget instance.
     *
     * @param \NotificationChannels\GoogleChat\Components\Button\AbstractButton|\NotificationChannels\GoogleChat\Components\Button\AbstractButton[]|null $buttons
     * @return self
     */
    public static function create($buttons = null): Buttons
    {
        $widget = new static;

        if ($buttons) {
            $widget->button($buttons);
        }

        return $widget;
    }
}
