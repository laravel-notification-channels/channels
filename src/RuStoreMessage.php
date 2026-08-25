<?php

declare(strict_types=1);

namespace NotificationChannels\RuStore;

use JsonException;
use NotificationChannels\RuStore\Resources\MessageAndroid;
use NotificationChannels\RuStore\Resources\MessageNotification;

/**
 * Структура push-уведомления.
 */
class RuStoreMessage
{
    /**
     * Create a new message instance.
     *
     * @param  array|null  $data  Объект, содержащий пары "key": value.
     * @param  MessageNotification|null  $notification  Базовый шаблон уведомления для использования на всех платформах.
     * @param  MessageAndroid|null  $android  Специальные параметры Android для сообщений.
     */
    public function __construct(
        public ?array $data = null,
        public ?MessageNotification $notification = null,
        public ?MessageAndroid $android = null,
    ) {
    }

    /**
     * Set the message data.
     *
     * @param  array|null  $data
     * @return $this
     */
    public function setData(?array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param  string  $token
     * @return string
     *
     * @throws JsonException
     */
    public function getPayload(string $token): string
    {
        return json_encode(['message' => compact('token') + $this->toArray()], JSON_THROW_ON_ERROR);
    }

    /**
     * Map the resource to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_filter(get_object_vars($this));
    }
}
