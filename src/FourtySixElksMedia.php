<?php
/**
 * Created by PhpStorm.
 * User: larsemil
 * Date: 2017-08-22
 * Time: 10:54
 */

namespace NotificationChannels\FourtySixElks;

use GuzzleHttp\Client;

class FourtySixElksMedia {

	/**
	 * @var string
	 */
	protected $endpoint = null;
	/**
	 * @var array
	 */
	protected $payload = [
		'lines'   => [],
		'subject' => ""
	];

	/**
	 * @var integer
	 */
	protected $phone_number;
	/**
	 * @var string
	 */
	protected $from;

	/**
	 * @var string
	 */
	protected $username;
	/**
	 * @var string
	 */
	protected $password;

	/**
	 * FourtySixElksMedia constructor.
	 */
	public function __construct() {
		$this->name     = config( 'services.46elks.username' );
		$this->password = config( 'services.46elks.password' );

		$this->client = new Client(
			[
				'headers' => [
					'Content-Type' => 'application/x-www-urlencoded'
				],
				'auth'    => [
					$this->username,
					$this->password
				]

			]
		);
	}

	/**
	 * @param $line
	 *
	 * @return $this
	 */
	public function line( $line ) {
		$this->payload['lines'][] = $line;

		return $this;
	}

	/**
	 * @param $subject
	 *
	 * @return $this
	 */
	public function subject( $subject ) {
		$this->payload['subject'] = $subject;

		return $this;
	}

	/**
	 * @param $phone_number
	 *
	 * @return $this
	 */
	public function to( $phone_number ) {
		$this->phone_number = $phone_number;

		return $this;
	}

	/**
	 * @param $string
	 *
	 * @return $this
	 */
	public function from( $string ) {
		$this->from = $string;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		return implode( PHP_EOL, $this->payload['lines'] );
	}

	/**
	 * @return mixed
	 */
	public function getSubject() {
		return $this->payload['subject'];
	}
}