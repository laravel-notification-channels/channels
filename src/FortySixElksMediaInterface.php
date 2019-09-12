<?php
namespace NotificationChannels\FortySixElks;


/**
 * Interface FourtSixElksMediaInterface
 * @package NotificationChannels\FortySixElks
 */
interface FortySixElksMediaInterface {

	/**
	 * FourtSixElksMediaInterface constructor.
	 */
	public function __construct();

	/**
	 * @return mixed
	 */
	public function send();

}
