<?php
/**
 * @link      http://horoshop.ua
 * @copyright Copyright (c) 2015-2018 Horoshop TM
 * @author    Andrey Telesh <andrey@horoshop.ua>
 */

namespace FtwSoft\NotificationChannels\Intercom;

class IntercomMessage
{

    const TYPE_EMAIL = 'email';

    const TYPE_INAPP = 'inapp';

    const TEMPLATE_PLAIN = 'plain';

    const TEMPLATE_PERSONAL = 'personal';

    /**
     * @param string $body
     *
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public static function create(?string $body = null): IntercomMessage
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
        if ($body !== null) {
            $this->body($body);
        }

        $this->inapp();
    }

    /**
     * @param string $body
     *
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function body(string $body): IntercomMessage
    {
        $this->payload['body'] = $body;

        return $this;
    }

    /**
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function email(): IntercomMessage
    {
        $this->payload['message_type'] = self::TYPE_EMAIL;

        return $this;
    }

    /**
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function inapp(): IntercomMessage
    {
        $this->payload['message_type'] = self::TYPE_INAPP;

        return $this;
    }

    /**
     * @param string $value
     *
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function subject(string $value): IntercomMessage
    {
        $this->payload['subject'] = $value;

        return $this;
    }

    /**
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function plain(): IntercomMessage
    {
        $this->payload['template'] = self::TEMPLATE_PLAIN;

        return $this;
    }

    /**
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function personal(): IntercomMessage
    {
        $this->payload['template'] = self::TEMPLATE_PERSONAL;

        return $this;
    }

    /**
     * @param string $adminId
     *
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function from(string $adminId): IntercomMessage
    {
        $this->payload['from'] = [
            'type' => 'admin',
            'id'   => $adminId
        ];

        return $this;
    }

    /**
     * @param array $value
     *
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function to(array $value): IntercomMessage
    {
        $this->payload['to'] = $value;

        return $this;
    }

    /**
     * @param string $id
     *
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function toUserId(string $id): IntercomMessage
    {
        $this->payload['to'] = [
            'type' => 'user',
            'id'   => $id
        ];

        return $this;
    }

    /**
     * @param string $email
     *
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function toUserEmail(string $email): IntercomMessage
    {
        $this->payload['to'] = [
            'type'  => 'user',
            'email' => $email
        ];

        return $this;
    }

    /**
     * @param string $id
     *
     * @return \FtwSoft\NotificationChannels\Intercom\IntercomMessage
     */
    public function toContactId(string $id): IntercomMessage
    {
        $this->payload['to'] = [
            'type' => 'contact',
            'id'   => $id
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