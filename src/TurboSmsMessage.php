<?php

namespace NotificationChannels\TurboSms;

class TurboSmsMessage
{
	
	/** @var string */
	public $content;
	
	/**
	 * @param string $content
	 *
	 * @return static
	 */
	public static function create ( $content = '' )
	{
		return new static( $content );
	}
	
	/**
	 * @param string $content
	 */
	public function __construct ( $content = '' )
	{
		$this->content = $content;
	}
	
	/**
	 * @param string $content
	 *
	 * @return $this
	 */
	public function content ( $content )
	{
		$this->content = $content;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getContent () : string
	{
		return $this->content;
	}
	
}
