<?php

namespace NotificationChannels\Webex;

use Closure;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use NotificationChannels\Webex\Exceptions\CouldNotCreateNotification;

/**
 * This class provides a fluent interface for creating a Webex Message representation.
 */
class WebexMessage implements Arrayable, JsonSerializable, Jsonable
{
    /**
     * The room ID of the message.
     *
     * @var string
     */
    public $roomId;

    /**
     * The parent message to reply to.
     *
     * @var string
     */
    public $parentId;

    /**
     * The person ID of the recipient when sending a direct 1:1 message.
     *
     * @var string
     */
    public $toPersonId;

    /**
     * The email address of the recipient when sending a direct 1:1 message.
     *
     * @var string
     */
    public $toPersonEmail;

    /**
     * The message, in plain text.
     *
     * @var string
     */
    public $text;

    /**
     * The message, in Markdown format.
     *
     * @var string
     *
     * @link https://developer.webex.com/docs/basics#formatting-messages
     */
    public $markdown;

    /**
     * Files to include in the message.
     *
     * **NOTES:**
     * 1. Despite the plural naming, the Webex HTTP API supports only one file per message.
     * 2. For supported MIME types and file size limits, please refer Webex HTTP API documentation.
     *
     * @var WebexMessageFile[]
     *
     * @see \NotificationChannels\Webex\WebexMessage::file()
     * @link https://developer.webex.com/docs/api/v1/messages/create-a-message
     */
    public $files;

    /**
     * Attachments to include in the message.
     *
     * **NOTES:**
     * 1. Despite the plural naming, the Webex HTTP API supports only one attachment per message.
     * 2. For supported attachment types, please refer Webex HTTP API documentation.
     *
     * @var WebexMessageAttachment[]
     *
     * @see \NotificationChannels\Webex\WebexMessage::attachment()
     * @link https://developer.webex.com/docs/api/v1/messages/create-a-message
     */
    public $attachments;

    /**
     * Set the content of the message, in plain text.
     *
     * @param  string  $content  message in plain text
     * @return WebexMessage
     */
    public function text(string $content): WebexMessage
    {
        $this->text = $content;

        return $this;
    }

    /**
     * Set the content of the message, in Markdown format.
     *
     * @param  string  $content  message in Markdown
     * @return WebexMessage
     *
     * @link https://developer.webex.com/docs/basics#formatting-messages
     */
    public function markdown(string $content): WebexMessage
    {
        $this->markdown = $content;

        return $this;
    }

    /**
     * Set the parent message to reply to.
     *
     * @param  string  $id  a Webex HTTP API Message resource identifier
     * @return $this
     *
     * @throws CouldNotCreateNotification
     */
    public function parentId(string $id): WebexMessage
    {
        $decodedId = $this->decodeApiId($id);
        if ($decodedId && $decodedId[1] === 'MESSAGE') {
            $this->parentId = $id;

            return $this;
        }

        throw CouldNotCreateNotification::invalidParentId($id);
    }

    /**
     * Set the recipient of the message.
     *
     * This function automatically determines if the recipient is a single person/bot (i.e. direct
     * 1:1 room/space) or a group room/space. Accordingly, it will nullify and then set exactly
     * one of {@see $toPersonEmail}, {@see $personId} or {@see $roomId} on the instance.
     *
     * @param  string  $recipient  an exiting Webex account email or Webex HTTP API resource identifier to a
     *                             person/bot or room/space
     * @return $this
     *
     * @throws CouldNotCreateNotification if the provided value is invalid or could not be used to
     *                                    automatically determine the recipient
     *
     * @uses \NotificationChannels\Webex\WebexMessage::decodeApiId()
     */
    public function to(string $recipient): WebexMessage
    {
        $this->toPersonEmail = $this->toPersonId = $this->roomId = null;

        /** {@param $recipient} is a valid email address **/
        if ((new EmailValidator)->isValid($recipient, new RFCValidation)) {
            $this->toPersonEmail = $recipient;

            return $this;
        }

        /** {@param $recipient} is a valid Webex HTTP API resource identifier **/
        if ($decodedTo = $this->decodeApiId($recipient)) {
            if ($decodedTo[1] === 'PEOPLE' ||
                $decodedTo[1] === 'APPLICATION') {
                $this->toPersonId = $recipient;

                return $this;
            }
            if ($decodedTo[1] === 'ROOM') {
                $this->roomId = $recipient;

                return $this;
            }
        }

        throw CouldNotCreateNotification::failedToDetermineRecipient();
    }

    /**
     * Set a file to include in the message.
     *
     * @param  Closure  $callback
     * @return WebexMessage
     *
     * @throws CouldNotCreateNotification when setting more than one file on the instance or
     *                                    setting a file when instance already has an attachment
     *
     * @uses \NotificationChannels\Webex\WebexMessage::$files
     * @uses \NotificationChannels\Webex\WebexMessageFile
     */
    public function file(Closure $callback): WebexMessage
    {
        if (! empty($this->files)) {
            throw CouldNotCreateNotification::multipleFilesNotSupported();
        }

        if (! empty($this->attachments)) {
            throw CouldNotCreateNotification::messageWithFileAndAttachmentNotSupported();
        }

        $this->files[] = $file = new WebexMessageFile;
        $callback($file);

        return $this;
    }

    /**
     * Set an attachment to include in the message.
     *
     * **NOTE:**
     * If both {@see $text} and {@see $markdown} are unassigned, {@see $text} will be
     * assigned an empty string value.
     *
     * @param  Closure  $callback
     * @return WebexMessage
     *
     * @throws CouldNotCreateNotification when setting more than one attachment on the instance or
     *                                    setting an attachment when instance already has a file
     *
     * @uses \NotificationChannels\Webex\WebexMessage::$attachments
     * @uses \NotificationChannels\Webex\WebexMessageAttachment
     */
    public function attachment(Closure $callback): WebexMessage
    {
        if (! empty($this->attachments)) {
            throw CouldNotCreateNotification::multipleAttachmentsNotSupported();
        }

        if (! empty($this->files)) {
            throw CouldNotCreateNotification::messageWithFileAndAttachmentNotSupported();
        }

        if (! isset($this->text) && ! isset($this->markdown)) {
            $this->text = '';
        }

        $this->attachments[] = $attachment = new WebexMessageAttachment;
        $callback($attachment);

        return $this;
    }

    /**
     * Get the instance as an array suitable for `multipart/form-data` request.
     *
     * @return array a two-dimensional array where outer index is numeric and inner keys are name,
     *               contents, headers (optional), and filename (optional)
     *
     * @internal
     */
    public function toArray(): array
    {
        $arr = [];

        foreach ($this as $key => $value) {
            if (isset($value) && $key !== 'files' && $key !== 'attachments') {
                $arr[] = [
                    'name' => $key,
                    'contents' => $value,
                ];
            }
        }

        if (isset($this->files)) {
            $files = array_map(function ($webexMessageFile): array {
                return $webexMessageFile->toArray();
            }, $this->files);

            $arr = array_merge($arr, $files);
        }

        if (isset($this->attachments)) {
            $attachments = array_map(function ($webexMessageAttachment): array {
                return $webexMessageAttachment->toArray();
            }, $this->attachments);

            $arr = array_merge($arr, $attachments);
        }

        return $arr;
    }

    /**
     * Get the instance as an array suitable for `application/json` request or {@see \json_encode()}.
     *
     * @return array an associative array of all set instance properties excluding files
     *
     * @internal
     */
    public function jsonSerialize(): array
    {
        $arr = [];

        foreach ($this as $key => $value) {
            if (isset($value) && $key !== 'files' && $key !== 'attachments') {
                $arr[$key] = $value;
            }
        }

        if (isset($this->attachments)) {
            $arr['attachments'] = array_map(function ($webexMessageAttachment): array {
                return $webexMessageAttachment->jsonSerialize();
            }, $this->attachments);
        }

        return $arr;
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
    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Decodes a Webex HTTP API resource identifier.
     *
     * Webex HTTP API resource identifiers start with `Y2lzY29zcGFyazovL3` and are URL safe Base64
     * encoded strings for legacy Cisco Spark URIs (i.e. `ciscospark://...`).
     * Some examples are listed below.
     * <br><br>
     *
     * People and Bot:
     * - `Y2lzY29zcGFyazovL3VzL1BFT1BMRS85OTRmYjAyZS04MWI1LTRmNDItYTllYy1iNzE2OGRlOWUzZjY`<br>
     *    => `ciscospark://us/PEOPLE/994fb02e-81b5-4f42-a9ec-b7168de9e3f6`
     * - `Y2lzY29zcGFyazovL3VzL0FQUExJQ0FUSU9OLzQxMmRhYjY4LTU3ZDAtNGU0Mi05MTJmLTIzODk2ODcyYTMwMg`<br>
     *    => `ciscospark://us/APPLICATION/412dab68-57d0-4e42-912f-23896872a302`
     *
     * Room/Space:
     * - `Y2lzY29zcGFyazovL3VzL1JPT00vOTU5Y2M0YzAtMjMxNC0xMWVjLWFhMDUtZWYxMmNlMmE5YjJi`<br>
     *    => `ciscospark://us/ROOM/959cc4c0-2314-11ec-aa05-ef12ce2a9b2b`
     *
     * Message:
     * - `Y2lzY29zcGFyazovL3VzL01FU1NBR0UvMjExN2ZjZTAtODcwMS0xMWVjLThjNDgtZmYzMmYwOWExMjNj`<br>
     *    =>`ciscospark://us/MESSAGE/2117fce0-8701-11ec-8c48-ff32f09a123c`
     *
     * @param  string  $id  any Webex HTTP API resource identifier
     * @return string[]|false the three components of decoded Webex HTTP API resource identifier
     *                        as an array of strings or false on failure
     */
    protected function decodeApiId(string $id)
    {
        $pattern = '#.*/(.*)/(.*)$#';
        $spark_uri = base64_decode($id);

        return preg_match($pattern, $spark_uri, $matches) ? $matches : false;
    }
}
