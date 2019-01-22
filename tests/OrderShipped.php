<?php
namespace NotificationChannels\Pushmix\Test;

use Illuminate\Notifications\Notification;
use NotificationChannels\Pushmix\PushmixMessage;
use NotificationChannels\Pushmix\PushmixChannel;

class OrderShipped extends Notification
{

  public function via($notifiable)
  {
      return [PushmixChannel::class];
  }

  public function toPushmix($to)
  {
    #dd($to);
    return (new PushmixMessage($to))
        ->title('Order Shipped')
        ->body('Your Order 123456 has been dispatched.')
        ->url('https://www.pushmix.co.uk');
  }
}
