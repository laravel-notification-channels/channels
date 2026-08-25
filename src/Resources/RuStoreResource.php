<?php

declare(strict_types=1);

namespace NotificationChannels\RuStore\Resources;

abstract class RuStoreResource
{
    /**
     * @param  ...$args
     * @return static
     */
    public static function create(...$args): static
    {
        return new static(...$args);
    }

    /**
     * Map the resource to an array.
     *
     * @return array
     */
    abstract public function toArray(): array;
}
