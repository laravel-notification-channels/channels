<?php

namespace NotificationChannels\Ntfy;

class NtfyMessage
{
    public $title;
    public $content;
    public $priority = 3;
    public $topic;
    public $tags = [];
    public $actions = [];
    public $attach;
    public $filename;

    public function title(string $title): NtfyMessage
    {
        $this->title = $title;

        return $this;
    }

    public function content(string $content): NtfyMessage
    {
        $this->content = $content;

        return $this;
    }

    public function priority(int $priority): NtfyMessage
    {
        $this->priority = $priority;

        return $this;
    }

    public function body(string $body): NtfyMessage
    {
        return $this->content($body);
    }

    public function topic(string $topic): NtfyMessage
    {
        $this->topic = $topic;

        return $this;
    }

    public function actions($actions): NtfyMessage
    {
        $this->actions = $actions;

        return $this;
    }
}
