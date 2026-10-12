<?php

namespace NotificationChannels\IssueBadge\Test;

use Illuminate\Notifications\Notifiable;

class TestNotifiable
{
    use Notifiable;

    public function __construct(
        public ?string $name = 'Ada Lovelace',
        public ?string $email = 'ada@example.com',
        protected mixed $route = null,
        protected bool $hasRouteMethod = false
    ) {
    }

    public function getKey(): int
    {
        return 1;
    }

    public function routeNotificationForIssuebadge($notification): mixed
    {
        return $this->route;
    }
}
