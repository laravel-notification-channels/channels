<?php

namespace NotificationChannels\FortySixElks;

use GuzzleHttp\Client;
use NotificationChannels\FortySixElks\Exceptions\MissingConfigNotification;

class FortySixElksMedia
{
    /**
     * @var string
     */
    const ENDPOINT = null;
    /**
     * @var array
     */
    protected $payload = [
        'lines'   => [],
        'subject' => '',
    ];

    /**
     * @var int
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
     * FortySixElksMedia constructor.
     */
    public function __construct()
    {
        $this->username = config('services.46elks.username');
        $this->password = config('services.46elks.password');
        if (!$this->username || !$this->password) {
            throw MissingConfigNotification::missingConfig();
        }

        $this->client = new Client(
            [
                'headers' => [
                    'Content-Type' => 'application/x-www-urlencoded',
                ],
                'auth'    => [
                    $this->username,
                    $this->password,
                ],
            ]
        );
    }

    /**
     * @param $line
     *
     * @return $this
     */
    public function line($line)
    {
        $this->payload['lines'][] = $line;

        return $this;
    }

    /**
     * @param $subject
     *
     * @return $this
     */
    public function subject($subject)
    {
        $this->payload['subject'] = $subject;

        return $this;
    }

    /**
     * @param $phone_number
     *
     * @return $this
     */
    public function to($phoneNumber)
    {
        $this->phone_number = $phoneNumber;

        return $this;
    }

    /**
     * @param $string
     *
     * @return $this
     */
    public function from($string)
    {
        $this->from = $string;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return implode(PHP_EOL, $this->payload['lines']);
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->payload['subject'];
    }
}
