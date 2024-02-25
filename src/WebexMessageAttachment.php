<?php

namespace NotificationChannels\Webex;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

/**
 * This class provides a fluent interface for creating a Webex Message Attachment representation.
 */
class WebexMessageAttachment implements Arrayable, JsonSerializable, Jsonable
{
    /**
     * The content type of the attachment.
     *
     * @var string
     */
    public $contentType = 'application/vnd.microsoft.card.adaptive';

    /**
     * The content of the attachment.
     *
     * @var array|mixed
     */
    public $content;

    /**
     * Set the content type of the attachment.
     *
     * @param  string  $contentType
     * @return WebexMessageAttachment
     */
    public function contentType(string $contentType): WebexMessageAttachment
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Set the content of the attachment.
     *
     * @param  array|mixed  $content
     * @return WebexMessageAttachment
     *
     * @link https://developer.webex.com/buttons-and-cards-designer
     * @link https://adaptivecards.io/
     */
    public function content($content): WebexMessageAttachment
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the instance as an array suitable for `multipart/form-data` request.
     *
     * @return array an associative array with name, contents, and headers as keys
     *
     * @internal
     */
    public function toArray(): array
    {
        return [
            'name' => 'attachments',
            'contents' => json_encode($this->jsonSerialize()),
            'headers' => ['Content-Type' => 'application/json'],
        ];
    }

    /**
     * Get the instance as an array suitable for `application/json` request or {@see \json_encode()}.
     *
     * @return array all instance properties as an associative array
     *
     * @internal
     */
    public function jsonSerialize(): array
    {
        return [
            'contentType' => $this->contentType,
            'content' => $this->content,
        ];
    }

    /**
     * Get the instance as JSON.
     *
     * This is a wrapper around PHP's {@see \json_encode()} and the instance's
     * {@see jsonSerialize()}.
     *
     * @param  int  $options  a bitmask flag parameter for {@see \json_encode()}
     * @return string the JSON representation
     *
     * @internal
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }
}
