<?php

namespace NotificationChannels\Webex\Exceptions;

use Exception;

class CouldNotCreateNotification extends Exception
{
    public static function invalidParentId(string $id): CouldNotCreateNotification
    {
        return new static("The id `$id` is not a valid message resource identifier.");
    }

    public static function failedToDetermineRecipient(): CouldNotCreateNotification
    {
        return new static('Failed to determine the message recipient.');
    }

    public static function messageWithFileAndAttachmentNotSupported(): CouldNotCreateNotification
    {
        return new static('Sending local file(s) and attachment(s) in the same message is not supported');
    }

    public static function multipleFilesNotSupported(): CouldNotCreateNotification
    {
        return new static('Sending multiple files in the same message is not supported');
    }

    public static function multipleAttachmentsNotSupported(): CouldNotCreateNotification
    {
        return new static('Sending multiple attachments in the same message is not supported');
    }
}
