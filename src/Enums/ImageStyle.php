<?php

namespace NotificationChannels\GoogleChat\Enums;

/**
 * @see https://developers.google.com/hangouts/chat/reference/message-formats/cards#headers
 */
interface ImageStyle
{
    public const SQUARE = 'IMAGE';

    public const CIRCULAR = 'AVATAR';
}
