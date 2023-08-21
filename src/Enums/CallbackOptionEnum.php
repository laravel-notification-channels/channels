<?php

namespace NotificationChannels\LaravelZenviaChannel\Enums;

enum CallbackOptionEnum: string
{
    case OPTION_ALL = 'ALL';
    case OPTION_FINAL = 'FINAL';
    case OPTION_NONE = 'NONE';
}
