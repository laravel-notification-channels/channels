<?php

namespace NotificationChannels\MstatGr;

use NotificationChannels\MstatGr\Exceptions\InvalidParameter;

class MstatGrMessage
{
    public string $content;
    public string|null $from;
    public string|null $to;

    public function __construct(string $content = '')
    {
        $this->content = $content;
        $this->from(\Config::get('services.mstat.default_from', ''));
        $this->to = null;
    }

    public function content(string $value): self
    {
        $this->content = $value;

        return $this;
    }

    public function from(string $value): self
    {
        if (is_numeric($value) && str($value)->length() > 15) {
            throw new InvalidParameter('From should be up to 15 numerice characters.');
        }

        if (! is_numeric($value) && str($value)->length() > 11) {
            throw new InvalidParameter('From should be up to 11 alphanumeric characters.');
        }

        $this->from = $value;

        return $this;
    }

    public function to(int $value): self
    {
        $this->to = $value;

        return $this;
    }
}
