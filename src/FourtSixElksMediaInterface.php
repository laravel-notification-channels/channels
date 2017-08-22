<?php
/**
 * Created by PhpStorm.
 * User: larsemil
 * Date: 2017-08-22
 * Time: 11:06
 */

namespace NotificationChannels\FourtySixElks;


/**
 * Interface FourtSixElksMediaInterface
 * @package NotificationChannels\FourtySixElks
 */
interface FourtSixElksMediaInterface {

	/**
	 * FourtSixElksMediaInterface constructor.
	 */
	public function __construct();

	/**
	 * @return mixed
	 */
	public function send();

}