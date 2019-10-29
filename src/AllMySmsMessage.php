<?php

namespace NotificationChannels\AllMySms;

use DateTime;

class AllMySmsMessage
{
    const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * The message content.
     *
     * @var string
     */
    public $content;

    /**
     * The sender name the message should sent from.
     *
     * @var string|null
     */
    public $sender;

    /**
     * The (optional) campaign name.
     *
     * @var string|null
     */
    public $campaign;

    /**
     * The (optional) date the message should sent at.
     *
     * @var string|null
     */
    public $sendAt;

    /**
     * The content parameters values.
     *
     * If you want to use parameters in your message you should add the following placeholder in your content:
     * "#param_x#" where x is the 1-based index of parameters array.
     *
     * <code>
     * $message->content("Hello #param_1# #param_2#")
     *     ->parameters([$user->first_name, $user->last_name]);
     * </code>
     *
     * @var array|null
     */
    public $parameters;

    /**
     * Create a new message instance.
     *
     * @param  string  $content
     * @return void
     */
    public function __construct(string $content = '')
    {
        $this->content = $content;
    }

    /**
     * Set message content.
     *
     * @param  string  $content
     * @return $this
     */
    public function content(string $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the sender name the message should sent from.
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
     * Set the campaign name.
     *
     * @param  string  $campaign
     * @return $this
     */
    public function campaign(string $campaign)
    {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * Set the date the message should sent at.
     *
     * @param  \DateTime|string  $sendAt
     * @return $this
     * @throws \Exception
     */
    public function sendAt($sendAt)
    {
        if (! $sendAt instanceof DateTime) {
            $sendAt = new DateTime($sendAt);
        }

        $this->sendAt = $sendAt->format(static::DATE_FORMAT);

        return $this;
    }

    /**
     * Set the message parameters.
     *
     * @param  array  $parameters
     * @return $this
     */
    public function parameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Get array representation of the message.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'message' => $this->content,
            'sender' => $this->sender,
            'campaign' => $this->campaign,
            'date' => $this->sendAt,
            'parameters' => $this->parameters,
        ];
    }
}
