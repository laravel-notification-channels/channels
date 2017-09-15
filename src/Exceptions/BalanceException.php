<?php

namespace NotificationChannels\TurboSms\Exceptions;

class BalanceException extends \Exception
{
	
	/**
	 * @return static
	 */
	public static function UnAuthorised ()
	{
		return new static( 'Unauthorized.', 401 );
	}
	
	/**
	 * @param int $balance
	 *
	 * @return static
	 */
	public static function InsufficientBalance ( int $balance = 0 )
	{
		return new static( 'Insufficient balance: "{$balance}" credits.', 402 );
	}
	
}
