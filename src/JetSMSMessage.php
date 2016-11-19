<?php
/**
 * Author: Hilmi Erdem KEREN
 * Date: 17/11/2016.
 */
namespace NotificationChannels\JetSMS;

use Carbon\Carbon;

/**
 * Class JetSMSMessage.
 */
final class JetSMSMessage implements JetSMSMessageInterface
{
    /**
     * The message content.
     *
     * @var string
     */
    private $content;

    /**
     * The number to be notified.
     *
     * @var string
     */
    private $number;

    /**
     * The message sender.
     *
     * @var string
     */
    private $originator;

    /**
     * The date message will be sent.
     *
     * @var Carbon|null
     */
    private $sendDate = null;

    /**
     * JetSMSMessage constructor.
     *
     * @param string             $content
     * @param string             $number
     * @param string|null        $originator
     * @param Carbon|string|null $sendDate
     */
    public function __construct($content, $number, $originator = null, $sendDate = null)
    {
        $this->content = $content;
        $this->number = $number;

        if (! is_null($originator)) {
            $this->originator = $originator;
        }

        if (! is_null($sendDate)) {
            $this->sendDate = $sendDate instanceof Carbon ? $sendDate : Carbon::createFromFormat($this->dateFormat(), $sendDate);
        }
    }

    /**
     * Convert the sms message to sms parameters.
     *
     * @return array
     */
    public function toRequestParams()
    {
        return array_filter([
            'Msisdns'        => $this->number(),
            'Messages'       => $this->content(),
            'SendDate'       => $this->sendDate() ? $this->sendDate()->format($this->dateFormat()) : null,
            'TransmissionID' => $this->originator(),
        ]);
    }

    /**
     * Get the short message.
     *
     * @return string
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * Get the to number.
     *
     * @return string
     */
    public function number()
    {
        return $this->number;
    }

    /**
     * Get the outbox name of the message.
     *
     * @return string
     */
    public function originator()
    {
        return $this->originator;
    }

    /**
     * Get the send date of the short message.
     *
     * @return Carbon|null
     */
    public function sendDate()
    {
        return $this->sendDate;
    }

    /**
     * Property getter.
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name();
    }

    /**
     * Get the api date format.
     *
     * @return string
     */
    private function dateFormat()
    {
        return 'Y-m-d H:i:s';
    }
}
