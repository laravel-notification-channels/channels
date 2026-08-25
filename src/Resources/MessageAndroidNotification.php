<?php

declare(strict_types=1);

namespace NotificationChannels\RuStore\Resources;

/**
 * Уведомление для отправки на устройства Android.
 */
class MessageAndroidNotification extends RuStoreResource
{
    /**
     * @param  string|null  $title  Название уведомления.
     * @param  string|null  $body  Основной текст уведомления.
     * @param  string|null  $icon  Значок уведомления.
     * @param  string|null  $color  Цвет значка уведомления в формате #rrggbb.
     * @param  string|null  $image  Содержит URL-адрес изображения, которое будет отображаться в уведомлении.
     * @param  string|null  $channel_id  Идентификатор канала уведомления.
     * @param  string|null  $click_action  Действие, связанное с кликом пользователя по уведомлению.
     * @param  int|null  $click_action_type  Необязательное поле, тип click_action
     *                                       0 - click_action будет использоваться как intent action (значение по умолчанию)
     *                                       1 - click_action будет использоваться как deep link
     */
    public function __construct(
        public ?string $title = null,
        public ?string $body = null,
        public ?string $icon = null,
        public ?string $color = null,
        public ?string $image = null,
        public ?string $channel_id = null,
        public ?string $click_action = null,
        public ?int $click_action_type = null,
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
     * Set the notification icon.
     *
     * @param  string|null  $icon
     * @return $this
     */
    public function icon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Set the notification color.
     *
     * @param  string|null  $color
     * @return $this
     */
    public function color(?string $color): self
    {
        $this->color = $color;

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
     * Set the notification image.
     *
     * @param  string|null  $channel_id
     * @return $this
     */
    public function channelId(?string $channel_id): self
    {
        $this->channel_id = $channel_id;

        return $this;
    }

    /**
     * Set the notification image.
     *
     * @param  string|null  $click_action
     * @return $this
     */
    public function clickAction(?string $click_action): self
    {
        $this->click_action = $click_action;

        return $this;
    }

    /**
     * Set the notification image.
     *
     * @param  int|null  $click_action_type
     * @return $this
     */
    public function clickActionType(?int $click_action_type): self
    {
        $this->click_action_type = $click_action_type;

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
