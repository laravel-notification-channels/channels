<?php

namespace NotificationChannels\Pushmix\Test;

use Illuminate\Notifications\Notification;
use NotificationChannels\Pushmix\PushmixChannel;
use NotificationChannels\Pushmix\PushmixMessage;

class OrderShipped extends Notification
{
    public function via($notifiable)
    {
        return [PushmixChannel::class];
    }

    public function toPushmix($to)
    {
        //dd($to);
        return (new PushmixMessage($to))
        ->title('Order Shipped')
        ->body('Your Order 123456 has been dispatched.')
        ->url('https://www.pushmix.co.uk');
    }
}
