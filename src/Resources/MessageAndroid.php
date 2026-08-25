<?php

declare(strict_types=1);

namespace NotificationChannels\RuStore\Resources;

/**
 * Специальные параметры Android для сообщений.
 */
class MessageAndroid extends RuStoreResource
{
    /**
     * @param  string|null  $ttl  Как долго (в секундах) сообщение должно храниться в хранилище. Пример: '3.5'.
     * @param  MessageAndroidNotification|null  $notification  Уведомление для отправки на устройства Android.
     */
    public function __construct(
        public ?string $ttl = null,
        public ?MessageAndroidNotification $notification = null
    ) {
    }

    /**
     * @param  string|null  $ttl
     * @return $this
     */
    public function ttl(?string $ttl): self
    {
        $this->ttl = $ttl;

        return $this;
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
