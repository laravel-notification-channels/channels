<?php
/**
 * Created by PhpStorm.
 * User: larsemil
 * Date: 2017-08-22
 * Time: 10:47
 */

namespace NotificationChannels\FourtySixElks;


class FourtySixElksSMS extends FourtySixElksMedia implements FourtSixElksMediaInterface {
	protected $endpoint = 'https://api.46elks.com/a1/SMS';
	public $type = 'SMS';

	/**
	 * FourtySixElksSMS constructor.
	 */
	public function __construct(){

		return parent::__construct();
	}

	/**
	 * @return $this
	 */
	public function send(){


		$response = $this->client->request('POST',$this->endpoint,[
			'form_params' => [
				'from' => $this->from,
				'message' => $this->getContent(),
				'to' => $this->phone_number,
			],

		]);

		return $this;
	}
}