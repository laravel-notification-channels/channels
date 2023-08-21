<?php

namespace NotificationChannels\LaravelZenviaChannel;

use NotificationChannels\LaravelZenviaChannel\Enums\CallbackOptionEnum;

class ZenviaSmsMessage extends ZenviaMessage
{
    /**
     * @var null|string
     */
    public $schedule;

    /**
     * @var null|CallbackOptionEnum
     */
    public $callbackOption;

    /**
     * @var null|string
     */
    public $id;

    /**
     * @var null|string
     */
    public $aggregateId;

    /**
     * @var null|bool
     */
    public $flashSms;

    /**
     * Set the message schedule.
     *
     * @param string $schedule
     *
     * @return $this
     */
    public function schedule(string $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * Set the message callback option.
     *
     * @param string $callbackOption
     *
     * @return $this
     */
    public function callbackOption(CallbackOptionEnum $callbackOption): self
    {
        $this->callbackOption = $callbackOption;

        return $this;
    }

    /**
     * Set the message ID.
     *
     * @param string $id
     *
     * @return $this
     */
    public function id(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the message aggregate ID.
     *
     * @param string $aggregateId
     *
     * @return $this
     */
    public function aggregateId(string $aggregateId): self
    {
        $this->aggregateId = $aggregateId;

        return $this;
    }

    /**
     * Set the message flash sms service use.
     *
     * @param bool $flashSms
     *
     * @return $this
     */
    public function flashSms(bool $flashSms): self
    {
        $this->flashSms = $flashSms;

        return $this;
    }
}
