<?php

namespace NotificationChannels\TurboSms\Exceptions;

class AuthException extends \Exception
{
	/**
	 * @param string $message
	 *
	 * @return static
	 */
	public static function NeedMoreParams ( string $message )
	{
		return new static( 'Bad Request: "{$message}"', 400 );
	}
	
	/**
	 * @param string $message
	 *
	 * @return static
	 */
	public static function WrongCredentials ( string $message )
	{
		return new static( 'Unauthorized: "{$message}"', 401 );
	}
	
	/**
	 * @param string $message
	 *
	 * @return static
	 */
	public static function AccountError ( string $message )
	{
		return new static( 'Account error: "{$message}"', 403 );
	}
	
	/**
	 * @param string $message
	 *
	 * @return static
	 */
	public static function serviceRespondedWithAnError ( string $message )
	{
		return new static( 'Auth error: "{$message}"', 400 );
	}
}
