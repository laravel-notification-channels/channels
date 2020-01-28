<?php

namespace NotificationChannels\Bitrix24;

class Bitrix24Message
{
    /**
     * @var string отправляемое сообщение
     */
    public $message;

    /**
     * @var bool кому пишем:
     * true - пользователю по ID пользователя,
     * false - именно в чат по ID чата
     */
    public $toUser = false;

    /**
     * Сформируем сообщение на основании шаблона Blade
     *
     * @param string $view view шаблон
     * @param array $data массив параметров для шаблона
     * @return $this
     * @throws \Throwable
     */
    public function view(string $view, array $data = [])
    {
        $this->message = view($view, $data)->render();

        return $this;
    }

    /**
     * Отправим простую строку в качестве сообщения
     *
     * @param string $message
     * @return $this
     */
    public function text(string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Сообщение предназначено для пользователя, а не для чата
     *
     * @return $this
     */
    public function toUser()
    {
        $this->toUser = true;

        return $this;
    }
}