<?php

namespace NotificationChannels\AwsSns;

class SnsMessage
{
    const PROMOTIONAL_SMS_TYPE = 'Promotional';

    const TRANSACTIONAL_SMS_TYPE = 'Transactional';

    /**
     * The default delivery type for the SMS message.
     *
     * @var bool
     */
    protected $promotional = true;

    /**
     * The body of the message.
     *
     * @var string
     */
    protected $body = '';

    public function __construct($content)
    {
        if (is_string($content)) {
            $this->body($content);
        }

        if (is_array($content)) {
            foreach ($content as $property => $value) {
                if (method_exists($this, $property)) {
                    $this->$property($value);
                }
            }
        }
    }

    /**
     * Creates a new instance of the message.
     *
     * @param  array      $data
     * @return SnsMessage
     */
    public static function create(array $data = [])
    {
        return new self($data);
    }

    /**
     * Sets the message body.
     *
     * @param  string $content
     * @return $this
     */
    public function body(string $content)
    {
        $this->body = trim($content);

        return $this;
    }

    /**
     * Get the message body.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get the SMS delivery type.
     *
     * @return string
     */
    public function getDeliveryType()
    {
        return $this->promotional ? self::PROMOTIONAL_SMS_TYPE : self::TRANSACTIONAL_SMS_TYPE;
    }

    /**
     * Sets the SMS delivery type as promotional.
     *
     * @param  bool  $active
     * @return $this
     */
    public function promotional(bool $active = true)
    {
        $this->promotional = $active;

        return $this;
    }

    /**
     * Sets the SMS delivery type as transactional.
     *
     * @param  bool  $active
     * @return $this
     */
    public function transactional(bool $active = true)
    {
        $this->promotional = ! $active;

        return $this;
    }
}
