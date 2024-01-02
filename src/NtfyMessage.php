<?php

namespace NotificationChannels\Ntfy;

use Illuminate\Support\Arr;

class NtfyMessage
{
    public $title;
    public $content;
    public $priority = 3;
    public $topic;

    public function title(string $title)
    {
        $this->title = $title;
        return $this;
    }

    public function content(string $content)
    {
        $this->content = $content;
        return $this;
    }

    public function priority(int $priority)
    {
        $this->priority = $priority;
        return $this;
    }

    public function body(string $body)
    {
        return $this->content($body);
    }

    public function topic(string $topic)
    {
        $this->topic = $topic;
        return $this;
    }


}
