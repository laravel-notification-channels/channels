<?php

namespace NotificationChannels\Bitrix24;

class Bitrix24Message
{
    /**
     * @var string send messages
     */
    public $message;

    /**
     * @var bool who we write to:
     * true - user by him ID,
     * false - to chat by chat ID
     */
    public $toUser = false;

    /**
     * Create a message based on the Blade template.
     *
     * @param string $view view of template
     * @param array $data parameters for the template
     * @return $this
     * @throws \Throwable
     */
    public function view(string $view, array $data = [])
    {
        $this->message = view($view, $data)->render();

        return $this;
    }

    /**
     * Send a string as a message.
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
     * The message is intended for the user, not for the chat.
     *
     * @return $this
     */
    public function toUser()
    {
        $this->toUser = true;

        return $this;
    }
}
