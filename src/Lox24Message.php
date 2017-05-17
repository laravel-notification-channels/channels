<?php

namespace NotificationChannels\Lox24;

use NotificationChannels\Lox24\Exceptions\CouldNotSendNotification;

class Lox24Message
{
    /**
     * SMS Types
     */
    const LOX24_SERVICE_DIRECT = 3323;
    const LOX24_SERVICE_ECONOMY = 3324;
    const LOX24_SERVICE_PRO = 3325;
    const MAXIMUM_SMS_LENGTH_UTF8       = 1530;
    const MAXIMUM_SMS_LENGTH_UNICODE    = 670;
    /**
     * @var array Params payload.
     */
    protected $payload = [
        'service'    => null,
        'text'       => null,
        'to'         => null,
        'encoding'   => null,
        'from'       => null,
        'timestamp'  => 0,
        'return'     => 'xml',
        'httphead'   => 0,
        'action'     => 'send',
    ];


    /**
     * Message constructor.
     *
     * @param string $text
     */
    public function __construct($text = '')
    {
        $this->setText($text);
    }

    /**
     * @param $text
     * @return Lox24Message
     */
    public static function create($text)
    {
        return new self($text);
    }

    /**
     * Set the message text
     *
     * @param $text string
     * @throws CouldNotSendNotification
     * @return $this
     */
    public function setText($text)
    {
        //detect encoding
        if ('UTF-8' === mb_detect_encoding($text, 'UTF-8')) {

            // set normal UTF-8 encoding for SMS
            $this->payload['encoding'] = 0;

            // Check max length
            if(mb_strlen($text) > self::MAXIMUM_SMS_LENGTH_UTF8) {
                throw new CouldNotSendNotification('Your message is longer then ' . self::MAXIMUM_SMS_LENGTH_UTF8 . ' characters in UTF-8');
            }

        } else {

            // set to Unicode SMS
            $this->payload['encoding'] = 1;

            // check max length
            if(mb_strlen($text) > self::MAXIMUM_SMS_LENGTH_UNICODE) {
                throw new CouldNotSendNotification('Your message is longer then' . self::MAXIMUM_SMS_LENGTH_UNICODE . ' characters in Unicode');
            }
        }


        $this->payload['text'] = $text;
        return $this;
    }


    /**
     * Set the receivers phone number
     *
     * @param $phoneNumber string
     *
     * @return $this
     */
    public function setTo($phoneNumber)
    {
        $this->payload['to'] = $phoneNumber;
        return $this;
    }


    /**
     * Is a receiver set?
     *
     * @return bool
     */
    public function toExists()
    {
        return $this->payload['to'] !== null;
    }


    /**
     * Set the senders name (max 11 chars text or 15 numbers)
     *
     * @param $senderName string
     * @return $this
     * @throws CouldNotSendNotification
     */
    public function setFrom($senderName)
    {
        if (!is_int($senderName) && mb_strlen($senderName) > 11) {
            throw new CouldNotSendNotification('Sender name is too long. Maximum of 11 characters allowed.');
        } elseif (is_int($senderName) && mb_strlen((string)$senderName) > 15) {
            throw new CouldNotSendNotification('Sender name is too long. Maximum of 15 numbers allowed.');
        }
        $this->payload['service'] = self::LOX24_SERVICE_PRO;
        $this->payload['from'] = $senderName;
        return $this;
    }


    /**
     * @param $serviceName
     * @return $this
     * @throws CouldNotSendNotification
     */
    public function setSmsService($serviceName)
    {
        $serviceName = intval($serviceName);
        if ($serviceName !== self::LOX24_SERVICE_ECONOMY &&
            $serviceName !== self::LOX24_SERVICE_DIRECT &&
            $serviceName !== self::LOX24_SERVICE_PRO
        ) {
            throw new CouldNotSendNotification('Invalid SMS Service ID requestet');
        }
        $this->payload['service'] = $serviceName;
        return $this;
    }


    /**
     * SMS will not be sent. Can be used for testing
     *
     * @return $this
     */
    public function testOnly()
    {
        $this->payload['action'] = 'info' ;

        return $this;
    }


    /**
     * When to send this SMS
     *
     * @return $this
     */
    public function sendAt(\DateTime $dateTime)
    {
        $this->payload['timestamp'] = $dateTime->getTimestamp();

        return $this;
    }


    /**
     * Returns params payload.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->payload;
    }
}
