<?php

namespace NotificationChannels\Zendesk;

use NotificationChannels\Exception\CouldNotCreateMessage;

class ZendeskMessage
{
    /** @var string */
    protected $subject;

    /** @var array */
    protected $requester = [];

    /** @var string */
    protected $description = '';

    /** @var string */
    protected $type;

    /** @var string */
    protected $status = 'new';

    /** @var array */
    protected $tags = [];

    /** @var string */
    protected $content = '';

    /** @var string */
    protected $htmlContent = '';

    /** @var bool */
    protected $isPublic = false;

    /** @var string */
    protected $priority = 'normal';

    /** @var array */
    protected $customFields = [];

    /** @var int */
    protected $groupId = '';

    /**
     * @param string $subject
     *
     * @return static
     */
    public static function create($subject = '', $description = '')
    {
        return new static($subject, $description);
    }

    /**
     * @param string $subject
     */
    public function __construct($subject = '', $description = '')
    {
        $this->subject = $subject;
        $this->description = $description;
        $this->content = $description;
    }

    /**
     * Set the ticket subject.
     *
     * @param $subject
     *
     * @return $this
     */
    public function subject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Set the ticket customer name.
     *
     * @param string $name
     * @param string $email
     *
     * @return $this
     */
    public function from($name, $email)
    {
        $this->requester = [
            'name' => $name,
            'email' => $email,
        ];

        return $this;
    }

    /**
     * Set the content message.
     *
     * @param $content
     *
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the HTML content of message.
     *
     * @param string $html
     *
     * @return $this
     */
    public function htmlContent($html)
    {
        $this->htmlContent = $html;

        return $this;
    }

    /**
     * Set the description.
     *
     * @param string $description
     *
     * @return $this
     */
    public function description($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set the ticket type.
     * Allowed values are problem, incident, question, or task.
     *
     * @param string $type
     *
     * @return $this
     */
    public function type($type)
    {
        if (! in_array($type, ['problem', 'incident', 'question', 'task'])) {
            throw CouldNotCreateMessage::invalidIndent($type);
        }
        $this->type = $type;

        return $this;
    }

    /**
     * Set the ticket priority.
     * Allowed values are urgent, high, normal, or low.
     *
     * @param string $priority
     *
     * @return $this
     */
    public function priority($priority)
    {
        if (! in_array($priority, ['urgent', 'high', 'normal', 'low'])) {
            throw CouldNotCreateMessage::invalidPriority($priority);
        }
        $this->priority = $priority;

        return $this;
    }

    /**
     * Set the ticket status.
     * Allowed values are new, open, pending, hold, solved or closed.
     *
     * @return $this
     */
    public function status($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Set the message to be public.
     *
     * @return $this
     */
    public function visible()
    {
        $this->isPublic = true;

        return $this;
    }

    /**
     * Add a tag to the ticket.
     *
     * @param string $tag
     *
     * @return $this
     */
    public function tag(array $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Set the value of custom field in the new ticket.
     *
     * @param int $id
     * @param string $value
     *
     * @return $this
     */
    public function customField($id, $value)
    {
        $this->customFields[] = [
            'id' => $id,
            'value' => $value,
        ];

        return $this;
    }

    /**
     * Set the value of group id.
     *
     * @param int $id
     * @param string $value
     *
     * @return $this
     */
    public function group($id)
    {
        $this->groupId = $id;

        return $this;
    }

    /**
     * Return the comment array.
     *
     * @see https://developer.zendesk.com/rest_api/docs/core/ticket_audits#audit-events Documentation of ticket comment.
     *
     * @return array
     */
    public function getComment()
    {
        $comment = [];

        if ($this->htmlContent !== '') {
            $comment['html_body'] = $this->htmlContent;
        }

        if ($this->content !== '') {
            $comment['body'] = $this->content;
        }

        $comment['public'] = $this->isPublic;

        return $comment;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'subject' => $this->subject,
            'comment' => $this->getComment(),
            'requester' => $this->requester,
            'description' => $this->description,
            'type' => $this->type,
            'status' => $this->status,
            'tags' => $this->tags,
            'priority' => $this->priority,
            'custom_fields' => $this->customFields,
            'group_id' => $this->groupId,
        ];
    }
}
