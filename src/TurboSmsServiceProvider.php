<?php

namespace NotificationChannels\TurboSms;

use Illuminate\Support\ServiceProvider;

class TurboSmsServiceProvider extends ServiceProvider
{
	
	/**
	 * Register the application services.
	 */
	public function register ()
	{
		$this->app->when( TurboSmsChannel::class )->needs( TurboSmsClient::class )->give( function () {
			$config = config( 'services.turbosms' );
			
			return new TurboSmsClient( $config[ 'login' ], $config[ 'password' ], $config[ 'sender' ] );
		} );
	}
	
}
