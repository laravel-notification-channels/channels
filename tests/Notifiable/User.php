<?php

declare(strict_types=1);

namespace NotificationChannels\RuStore\Test\Notifiable;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // public $timestamps = false;

    // protected $fillable = ['email'];

    private ?array $tokens = null;

    public function setTokens(array $tokens): self
    {
        $this->tokens = $tokens;

        return $this;
    }

    /**
     * @return array
     */
    public function routeNotificationForRuStore(): array
    {
        return $this->tokens ?? [env('RUSTORE_EXAMPLE_PUSH_TOKEN', 'none')];
    }
}
