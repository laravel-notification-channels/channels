<?php

declare(strict_types=1);

namespace NotificationChannels\RuStore\Resources;

/**
 * Базовый шаблон уведомления для использования на всех платформах.
 */
class MessageNotification extends RuStoreResource
{
    /**
     * Create a new notification instance.
     *
     * @param  string|null  $title  Название уведомления
     * @param  string|null  $body  Основной текст уведомления
     * @param  string|null  $image  Содержит URL-адрес изображения, которое будет отображаться в уведомлении.
     */
    public function __construct(
        public ?string $title = null,
        public ?string $body = null,
        public ?string $image = null
    ) {
    }

    /**
     * @param  string|null  $title
     * @return $this
     */
    public function title(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the notification body.
     *
     * @param  string|null  $body
     * @return $this
     */
    public function body(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Set the notification image.
     *
     * @param  string|null  $image
     * @return $this
     */
    public function image(?string $image): self
    {
        $this->image = $image;

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
