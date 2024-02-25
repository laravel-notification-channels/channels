<?php

namespace NotificationChannels\Webex;

use GuzzleHttp\Psr7\Utils;
use Illuminate\Contracts\Support\Arrayable;

/**
 * This class provides a fluent interface for creating a Webex Message File representation.
 */
class WebexMessageFile implements Arrayable
{
    /**
     * The path for the file.
     *
     * @var string
     */
    public $path;

    /**
     * The user provided name for the file.
     *
     * @var string
     */
    public $name;

    /**
     * The user provided MIME type for the file.
     *
     * @var string
     */
    public $type;

    /**
     * Set the path for the file.
     *
     * @param  string  $path
     * @return WebexMessageFile
     */
    public function path(string $path): WebexMessageFile
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Set the user provided name for the file.
     *
     * @param  string  $name
     * @return WebexMessageFile
     */
    public function name(string $name): WebexMessageFile
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the user provided MIME type for the file.
     *
     * @param  string  $type
     * @return WebexMessageFile
     */
    public function type(string $type): WebexMessageFile
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the instance as an array suitable for `multipart/form-data` request.
     *
     * This function assumes that the {@see $path} can be read.
     *
     * @return array an associative array with name, contents, headers (optional), and filename
     *               (optional) as keys
     *
     * @internal
     */
    public function toArray(): array
    {
        $arr = [
            'name' => 'files',
            'contents' => Utils::tryFopen($this->path, 'r'),
        ];

        if (isset($this->name)) {
            $arr['filename'] = $this->name;
        }

        if (isset($this->type)) {
            $arr['headers'] = ['Content-Type' => $this->type];
        }

        return $arr;
    }
}
