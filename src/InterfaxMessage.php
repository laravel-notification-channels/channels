<?php

namespace NotificationChannels\Interfax;

use Illuminate\Support\Arr;

class InterfaxMessage
{
    protected $files;
    protected $stream;
    protected $filename;
    protected $method;
    protected $statusCheck = false;
    public $user;

    const FILES = 'files';
    const STREAM = 'stream';

    public function file(string $file)
    {
        $this->files = Arr::wrap($file);
        $this->method = static::FILES;

        return $this;
    }

    public function files(array $files)
    {
        $this->files = $files;
        $this->method = static::FILES;

        return $this;
    }

    public function stream($stream, string $filename)
    {
        $this->stream = $stream;
        $this->filename = $filename;
        $this->method = static::STREAM;

        return $this;
    }

    public function checkStatus(bool $shouldCheck = true)
    {
        $this->statusCheck = $shouldCheck;

        return $this;
    }

    public function shouldCheckStatus(): bool
    {
        return $this->statusCheck;
    }

    /**
     * Set a user who can be notified in case the fax fails to send.
     * @param  mixed $notifiable [description]
     * @return InterfaxMessage
     */
    public function user($notifiable)
    {
        $this->user = $notifiable;

        return $this;
    }

    public function makeFiles(): array
    {
        if ($this->method === static::STREAM) {
            return [
                [
                    $this->stream,
                    [
                        'name' => $this->filename,
                        'mime_type' => 'application/pdf',
                    ],
                ],
            ];
        }

        return $this->files;
    }
}
