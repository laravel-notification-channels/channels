<?php

namespace NotificationChannels\IssueBadge\Test;

use DateTimeImmutable;
use NotificationChannels\IssueBadge\IssueBadgeMessage;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class IssueBadgeMessageTest extends TestCase
{
    #[Test]
    public function it_builds_the_issue_payload_fluently(): void
    {
        $message = IssueBadgeMessage::create('badge_123')
            ->name('Ada Lovelace')
            ->email('ada@example.com')
            ->issueDate('2026-10-09')
            ->expireDate(new DateTimeImmutable('2027-10-09 15:00:00'))
            ->idempotencyKey('course-42-user-7')
            ->metadata(['score' => 95])
            ->meta('course', 'CS101');

        $this->assertSame([
            'badge_id' => 'badge_123',
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.com',
            'idempotency_key' => 'course-42-user-7',
            'issue_date' => '2026-10-09',
            'expire_date' => '2027-10-09',
            'metadata' => ['score' => 95, 'course' => 'CS101'],
        ], $message->toArray());
    }

    #[Test]
    public function it_omits_unset_fields(): void
    {
        $message = (new IssueBadgeMessage())->badge('badge_123')->name('Ada');

        $this->assertSame(['badge_id' => 'badge_123', 'name' => 'Ada'], $message->toArray());
        $this->assertArrayNotHasKey('metadata', $message->toArray());
    }

    #[Test]
    public function it_exposes_badge_name_and_email(): void
    {
        $message = new IssueBadgeMessage('b1');

        $this->assertSame('b1', $message->getBadgeId());
        $this->assertNull($message->getName());
        $this->assertNull($message->getEmail());

        $message->name('Ada')->email('ada@example.com');

        $this->assertSame('Ada', $message->getName());
        $this->assertSame('ada@example.com', $message->getEmail());
    }

    #[Test]
    public function metadata_calls_merge(): void
    {
        $message = IssueBadgeMessage::create('b1')->metadata(['a' => 1])->metadata(['b' => 2])->meta('a', 3);

        $this->assertSame(['a' => 3, 'b' => 2], $message->toArray()['metadata']);
    }
}
