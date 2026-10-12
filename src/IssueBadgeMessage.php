<?php

namespace NotificationChannels\IssueBadge;

use DateTimeInterface;

class IssueBadgeMessage
{
    protected ?string $badgeId = null;

    protected ?string $name = null;

    protected ?string $email = null;

    protected ?string $issueDate = null;

    protected ?string $expireDate = null;

    protected ?string $idempotencyKey = null;

    protected array $metadata = [];

    public function __construct(?string $badgeId = null)
    {
        $this->badgeId = $badgeId;
    }

    public static function create(?string $badgeId = null): self
    {
        return new static($badgeId);
    }

    /** The badge template to issue (its ID from the IssueBadge dashboard or API). */
    public function badge(string $badgeId): self
    {
        $this->badgeId = $badgeId;

        return $this;
    }

    /** Recipient name printed on the certificate. Defaults to the notifiable's name. */
    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /** Recipient email; IssueBadge emails the certificate when set. Defaults to the notifiable's email. */
    public function email(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /** Issue date (defaults to today on the server). */
    public function issueDate(DateTimeInterface|string $date): self
    {
        $this->issueDate = $this->formatDate($date);

        return $this;
    }

    /** Expiration date. */
    public function expireDate(DateTimeInterface|string $date): self
    {
        $this->expireDate = $this->formatDate($date);

        return $this;
    }

    /** Unique key: sending the same key twice returns the existing issue instead of a duplicate. */
    public function idempotencyKey(string $key): self
    {
        $this->idempotencyKey = $key;

        return $this;
    }

    /** Custom field values defined on the badge template (merged with earlier calls). */
    public function metadata(array $metadata): self
    {
        $this->metadata = array_merge($this->metadata, $metadata);

        return $this;
    }

    /** Set one custom field value. */
    public function meta(string $key, mixed $value): self
    {
        $this->metadata[$key] = $value;

        return $this;
    }

    public function getBadgeId(): ?string
    {
        return $this->badgeId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    /** Request body for POST /api/v1/issue/create (null fields omitted). */
    public function toArray(): array
    {
        return array_filter([
            'badge_id' => $this->badgeId,
            'name' => $this->name,
            'email' => $this->email,
            'idempotency_key' => $this->idempotencyKey,
            'issue_date' => $this->issueDate,
            'expire_date' => $this->expireDate,
            'metadata' => $this->metadata ?: null,
        ], fn ($value) => $value !== null);
    }

    protected function formatDate(DateTimeInterface|string $date): string
    {
        return $date instanceof DateTimeInterface ? $date->format('Y-m-d') : $date;
    }
}
