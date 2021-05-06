<?php

namespace NotificationChannels\GoogleChat;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use NotificationChannels\GoogleChat\Concerns\ValidatesCardComponents;
use NotificationChannels\GoogleChat\Widgets\AbstractWidget;

class Section implements Arrayable
{
    use ValidatesCardComponents;

    /**
     * The section payload.
     *
     * @var array
     */
    protected array $payload = [
        'widgets' => [],
    ];

    /**
     * Set the section header text.
     *
     * @param string $text
     * @return self
     */
    public function header(string $text): Section
    {
        $this->payload['header'] = $text;

        return $this;
    }

    /**
     * Add one or more widgets to this section.
     *
     * @param \NotificationChannels\GoogleChat\Widgets\AbstractWidget|\NotificationChannels\GoogleChat\Widgets\AbstractWidget[] $widget
     * @return self
     */
    public function widget($widget): Section
    {
        $widgets = Arr::wrap($widget);

        $this->guardOnlyInstancesOf(AbstractWidget::class, $widgets);

        $this->payload['widgets'] = array_merge($this->payload['widgets'], $widgets);

        return $this;
    }

    /**
     * Serialize the section to an array representation.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->payload;
    }

    /**
     * Return a new Google Chat Section instance.
     *
     * @param \NotificationChannels\GoogleChat\Widgets\AbstractWidget|\NotificationChannels\GoogleChat\Widgets\AbstractWidget[] $widgets
     * @return self
     */
    public static function create($widgets = null): Section
    {
        $section = new static;

        if ($widgets) {
            $section->widget($widgets);
        }

        return $section;
    }
}
