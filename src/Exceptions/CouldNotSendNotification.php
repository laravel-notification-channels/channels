<?php

namespace NotificationChannels\Bitrix24\Exceptions;

class CouldNotSendNotification extends \Exception
{
    /**
     * @return CouldNotSendNotification
     */
    public static function notChatId()
    {
        return new static("Сhat id was not transferred.");
    }

    /**
     * @return CouldNotSendNotification
     */
    public static function notToken()
    {
        return new static("Not found token in the config/bitrix24_notice.");
    }

    /**
     * @return CouldNotSendNotification
     */
    public static function notUserId()
    {
        return new static("Not found fromUserId in the config/bitrix24_notice.");
    }

    /**
     * @return CouldNotSendNotification
     */
    public static function notDomain()
    {
        return new static("Not found domain in the config/bitrix24_notice.");
    }

    /**
     * @param $message
     *
     * @return CouldNotSendNotification
     */
    public static function notConnect($message)
    {
        return new static($message);
    }
}
