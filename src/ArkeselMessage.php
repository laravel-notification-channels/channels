<?php

namespace NotificationChannels\ArkeselSms;

use Illuminate\Support\Arr;

class ArkeselMessage
{
    /**
     * the SMS message to be sent.
     *
     * @var string
     *
     * A one-page message = 160 character, so you send a message with 200 characters, the messages will be 2 pages.
     */
    public string $message;

    /**
     * Arkesel SMS API Key
     *
     * @var string
     */
    public ?string $apiKey;

    /**
     * schedule when the message should be sent.
     *
     * @var string
     * @see https://developers.arkesel.com/#operation/send_schedule_sms_v1
     * @see https://developers.arkesel.com/#operation/send_sms
     */
    public ?string $schedule;

    /**
     * the name or number that identifies the sender of an SMS message.
     *
     * @var string
     */
    public ?string $sender;

    /**
     * phone numbers to which to Send message to.
     *
     * @var string|array
     *
     * SMS API V1: "233544919953,233544919953,233544919953"
     *
     * SMS API V2: ["233544919953", "233544919953", "233544919953"]
     */
    public string|array|null $recipients;

    /**
     * A URL that will be called to notify you about the status of the message to a particular number.
     *
     * @var string
     *
     * @see developershttps://developers.arkesel.com/#operation/send_sms
     */
    public ?string $callbackUrl;

    /**
     * if true, sms messages are not forwarded to the mobile network providers for delivery,
     *  hence you are not billed for the operation. Use this to test your application.
     *
     * @var bool
     */
    public bool $sandbox;

    /**
     * new ArkeselMessage instance.
     *
     * @param  string  $message
     *
     */
    public function __construct(
        string $message = '',
        string|array $recipients = null,
        string $apiKey = null,
        string $schedule = null,
        string $sender = null,
        string $callbackUrl = null,
        bool $sandbox = false,
    ) {
        $this->message = $message;
        $this->apiKey = $apiKey;
        $this->schedule = $schedule;
        $this->sender = $sender;
        $this->recipients = $recipients;
        $this->callbackUrl = $callbackUrl;
        $this->sandbox = $sandbox;
    }

    /**
     * set the message to be sent.
     *
     * @param  string  $message
     * @return $this
     */
    public function message(string $message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * sets the API key to used to authenticate the request
     * Overrides the API key set in the `.env` file.
     *
     * @param  string  $apiKey
     * @return $this
     */
    public function apiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }


    /**
     *  set/schedule when the message should be sent.
     *
     * @var string
     * @see https://developers.arkesel.com/#operation/send_schedule_sms_v1
     * @see https://developers.arkesel.com/#operation/send_sms
     *
     * @param  string  $schedule
     * @return $this
     */
    public function schedule(string $schedule)
    {
        $this->schedule = $schedule;
        return $this;
    }

    /**
     * set the name or number that identifies the sender of an SMS message.
     *
     * @param  string  $sender
     * @return $this
     */
    public function sender(string $sender)
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * set the phone numbers to receive the sms.
     *
     * @var string|array
     *
     * SMS API V1: "233544919953,233544919953,233544919953"
     *
     * SMS API V2: ["233544919953", "233544919953", "233544919953"]
     *
     * @param  string|array  $recipients
     * @return $this
     */
    public function recipients(string|array $recipients)
    {
        $this->recipients = $recipients;
        return $this;
    }

    /**
     * set a URL that will be called to notify you about the status of the message to a particular number.
     *
     * @param  string  $callbackUrl
     * @see developershttps://developers.arkesel.com/#operation/send_sms
     *
     *  @return $this
     */
    public function callbackUrl(string $callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
        return $this;
    }

    /**
     * set the environment for sending sms.
     * if true, sms messages are not forwarded to the mobile network providers for delivery,
     * hence you are not billed for the operation. Use this to test your application.
     *
     * @param  boolean  sandbox
     * @return $this
     */
    public function sandbox(bool $sandbox)
    {
        $this->sandbox = $sandbox;
        return $this;
    }
}
