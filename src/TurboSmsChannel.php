<?php

namespace NotificationChannels\TurboSms;

use Illuminate\Notifications\Notification;
use NotificationChannels\TurboSms\Exceptions\AuthException;
use NotificationChannels\TurboSms\Exceptions\BalanceException;
use NotificationChannels\TurboSms\Exceptions\CouldNotSendNotification;

class TurboSmsChannel
{
	/** @var TurboSmsClient */
	protected $client;
	
	/**
	 * @param TurboSmsClient $client
	 */
	public function __construct ( TurboSmsClient $client )
	{
		$this->client = $client;
	}
	
	/**
	 * Send the given notification.
	 *
	 * @param mixed        $notifiable
	 * @param Notification $notification
	 *
	 * @throws AuthException
	 * @throws BalanceException
	 * @throws CouldNotSendNotification
	 */
	public function send ( $notifiable, Notification $notification ) : void
	{
		if ( !$to = $notifiable->routeNotificationFor( 'turbosms' ) ) {
			return;
		}
		
		if ( is_string( $to ) ) {
			$to = explode( ',', $to );
		}
		
		$message = $notification->toTurboSms( $notifiable );
		
		if ( is_string( $message ) ) {
			$message = new TurboSmsMessage( $message );
		}
		
		$this->client->send( $to, $message->getContent(), $message->getSender() );
	}
}
