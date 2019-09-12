<?php
/**
 * Created by PhpStorm.
 * User: larsemil
 * Date: 2017-08-23
 * Time: 14:12
 */

namespace NotificationChannels\FortySixElks\Exceptions;


class CouldNotUseNotification extends \Exception {
	public static function missingMethod()
	{
		return new static('Your notification does not have the to46Elks method. Please create.');
	}

	public static function missingConfig()
    {
        return new static('46 elks username and / or password are missing. Did you add it to service array and check your .env file?');
    }
}
