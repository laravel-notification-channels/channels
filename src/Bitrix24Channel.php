<?php

namespace NotificationChannels\Bitrix24;

use Illuminate\Notifications\Notification;
use NotificationChannels\Bitrix24\Api\Bitrix24;
use NotificationChannels\Bitrix24\Exceptions\NoticeBitrix24Exception;

class Bitrix24Channel
{
    /**
     * @var Bitrix24
     */
    protected $bitrix24;

    /**
     * Bitrix24Channel constructor.
     * @param Bitrix24 $bitrix24
     */
    public function __construct(Bitrix24 $bitrix24)
    {
        $this->bitrix24 = $bitrix24;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     * @throws
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toBitrix24($notifiable);

        if (is_object($notifiable)) {
            $notifiable = $notifiable->routeNotificationFor('bitrix24');
        }

        if (empty($notifiable)) {
            throw new NoticeBitrix24Exception('Сhat id was not transferred');
        }

        if ($message->toUser === true) {
            $typeOfChat = 'USER_ID';
        } else {
            $typeOfChat = 'CHAT_ID';
        }

        $params = [
            $typeOfChat => $notifiable,
            'MESSAGE' => $message->message,
        ];

        $this->bitrix24->send($params);
    }
}
