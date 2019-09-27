<?php

namespace FtwSoft\NotificationChannels\Intercom;

class IntercomMessage
{
    public const TYPE_EMAIL = 'email';

    public const TYPE_INAPP = 'inapp';

    public const TEMPLATE_PLAIN = 'plain';

    public const TEMPLATE_PERSONAL = 'personal';

    /**
     * @param string $body
     *
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public static function create(?string $body = null): self
    {
        return new static($body);
    }

    /**
     * @var array
     */
    public $payload;

    /**
     * IntercomMessage constructor.
     *
     * @param string $body
     */
    public function __construct(?string $body = null)
    {
        if (null !== $body) {
            $this->body($body);
        }

        $this->inapp();
    }

    /**
     * @param string $body
     *
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function body(string $body): self
    {
        $this->payload['body'] = $body;

        return $this;
    }

    /**
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function email(): self
    {
        $this->payload['message_type'] = self::TYPE_EMAIL;

        return $this;
    }

    /**
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function inapp(): self
    {
        $this->payload['message_type'] = self::TYPE_INAPP;

        return $this;
    }

    /**
     * @param string $value
     *
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function subject(string $value): self
    {
        $this->payload['subject'] = $value;

        return $this;
    }

    /**
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function plain(): self
    {
        $this->payload['template'] = self::TEMPLATE_PLAIN;

        return $this;
    }

    /**
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function personal(): self
    {
        $this->payload['template'] = self::TEMPLATE_PERSONAL;

        return $this;
    }

    /**
     * @param string $adminId
     *
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function from(string $adminId): self
    {
        $this->payload['from'] = [
            'type' => 'admin',
            'id'   => $adminId,
        ];

        return $this;
    }

    /**
     * @param array $value
     *
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function to(array $value): self
    {
        $this->payload['to'] = $value;

        return $this;
    }

    /**
     * @param string $id
     *
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function toUserId(string $id): self
    {
        $this->payload['to'] = [
            'type' => 'user',
            'id'   => $id,
        ];

        return $this;
    }

    /**
     * @param string $email
     *
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function toUserEmail(string $email): self
    {
        $this->payload['to'] = [
            'type'  => 'user',
            'email' => $email,
        ];

        return $this;
    }

    /**
     * @param string $id
     *
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function toContactId(string $id): self
    {
        $this->payload['to'] = [
            'type' => 'contact',
            'id'   => $id,
        ];

        return $this;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return isset(
            $this->payload['body'],
            $this->payload['from'],
            $this->payload['to']
        );
    }

    /**
     * @return bool
     */
    public function toIsGiven(): bool
    {
        return isset($this->payload['to']);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->payload;
    }
}
