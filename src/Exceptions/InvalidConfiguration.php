<?php

namespace NotificationChannels\IssueBadge\Exceptions;

class InvalidConfiguration extends \Exception
{
    public static function apiKeyNotSet(): self
    {
        return new static(
            'No IssueBadge API key configured. Add services.issuebadge.key to config/services.php '.
            '(ISSUEBADGE_API_KEY in your .env).'
        );
    }
}
