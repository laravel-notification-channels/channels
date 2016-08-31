<?php

namespace NotificationChannels\Cmsms;

use NotificationChannels\Cmsms\Exceptions\InvalidMessage;

class CmsmsMessage
{
    /**
     * @var string
     */
    public $body;

    /**
     * @var string
     */
    public $originator;

    /**
     * @var string
     */
    public $recipient;

    /**
     * @var string
     */
    public $reference;

    /**
     * @param string $body
     */
    public function __construct($body = '')
    {
        if (!empty($body)) {
            $this->setBody($body);
        }
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = trim($body);

        return $this;
    }

    /**
     * @param string|int $originator
     * @return $this
     */
    public function setOriginator($originator)
    {
        $this->originator = (string) $originator;

        return $this;
    }

    /**
     * @param string|int $recipient
     * @return $this
     */
    public function setRecipient($recipient)
    {
        $this->recipient = (string) $recipient;

        return $this;
    }

    /**
     * @param string $reference
     * @return $this
     * @throws InvalidMessage
     */
    public function setReference($reference)
    {
        if (empty($reference) || strlen($reference) > 32 || !ctype_alnum($reference)) {
            throw InvalidMessage::invalidReference($reference);
        }

        $this->reference = $reference;

        return $this;
    }

    /**
     * @return array
     */
    public function toXmlArray()
    {
        return array_filter([
            'BODY' => $this->body,
            'FROM' => $this->originator,
            'TO' => $this->recipient,
            'REFERENCE' => $this->reference,
        ]);
    }

    /**
     * @param string $body
     * @return static
     */
    public static function create($body = '')
    {
        return new static($body);
    }
}
