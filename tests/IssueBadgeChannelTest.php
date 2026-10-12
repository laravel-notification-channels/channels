<?php

namespace NotificationChannels\IssueBadge\Test;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use NotificationChannels\IssueBadge\Exceptions\CouldNotSendNotification;
use NotificationChannels\IssueBadge\IssueBadge;
use NotificationChannels\IssueBadge\IssueBadgeChannel;
use NotificationChannels\IssueBadge\IssueBadgeMessage;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class IssueBadgeChannelTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected IssueBadge|Mockery\MockInterface $client;

    protected IssueBadgeChannel $channel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = Mockery::mock(IssueBadge::class);
        $this->channel = new IssueBadgeChannel($this->client);
    }

    protected function expectIssue(array $payload, array $response = ['success' => true, 'IssueId' => 'i1']): void
    {
        $this->client->shouldReceive('issue')->once()->with($payload)->andReturn($response);
    }

    #[Test]
    public function it_issues_a_badge_using_the_notifiable_name_and_email(): void
    {
        $this->expectIssue(['badge_id' => 'b1', 'name' => 'Ada Lovelace', 'email' => 'ada@example.com']);

        $response = $this->channel->send(new TestNotifiable(), new TestNotification(IssueBadgeMessage::create('b1')));

        $this->assertSame('i1', $response['IssueId']);
    }

    #[Test]
    public function message_fields_take_precedence_over_the_notifiable(): void
    {
        $this->expectIssue(['badge_id' => 'b1', 'name' => 'Countess Lovelace', 'email' => 'ada@babbage.org', 'metadata' => ['score' => 100]]);

        $message = IssueBadgeMessage::create('b1')->name('Countess Lovelace')->email('ada@babbage.org')->meta('score', 100);

        $this->channel->send(new TestNotifiable(), new TestNotification($message));
    }

    #[Test]
    public function a_string_route_is_used_as_the_email(): void
    {
        $this->expectIssue(['badge_id' => 'b1', 'name' => 'Ada Lovelace', 'email' => 'routed@example.com']);

        $this->channel->send(new TestNotifiable(route: 'routed@example.com'), new TestNotification(IssueBadgeMessage::create('b1')));
    }

    #[Test]
    public function an_array_route_supplies_name_and_email(): void
    {
        $this->expectIssue(['badge_id' => 'b1', 'name' => 'Routed Name', 'email' => 'routed@example.com']);

        $notifiable = new TestNotifiable(name: 'Ignored', email: 'ignored@example.com', route: ['name' => 'Routed Name', 'email' => 'routed@example.com']);

        $this->channel->send($notifiable, new TestNotification(IssueBadgeMessage::create('b1')));
    }

    #[Test]
    public function a_badge_id_string_is_accepted_as_the_message(): void
    {
        $this->expectIssue(['badge_id' => 'b1', 'name' => 'Ada Lovelace', 'email' => 'ada@example.com']);

        $this->channel->send(new TestNotifiable(), new TestNotification('b1'));
    }

    #[Test]
    public function it_works_with_on_demand_notifications(): void
    {
        $this->expectIssue(['badge_id' => 'b1', 'name' => 'Grace Hopper', 'email' => 'grace@example.com']);

        $notifiable = (new AnonymousNotifiable())->route('issuebadge', ['name' => 'Grace Hopper', 'email' => 'grace@example.com']);

        $this->channel->send($notifiable, new TestNotification(IssueBadgeMessage::create('b1')));
    }

    #[Test]
    public function email_is_omitted_when_nothing_provides_one(): void
    {
        $this->expectIssue(['badge_id' => 'b1', 'name' => 'No Email']);

        $this->channel->send(new TestNotifiable(name: 'No Email', email: null), new TestNotification(IssueBadgeMessage::create('b1')));
    }

    #[Test]
    public function it_throws_when_the_notification_has_no_to_issue_badge_method(): void
    {
        $this->client->shouldNotReceive('issue');
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('toIssueBadge');

        $this->channel->send(new TestNotifiable(), new class extends Notification {});
    }

    #[Test]
    public function it_throws_when_the_message_is_not_a_message(): void
    {
        $this->client->shouldNotReceive('issue');
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('IssueBadgeMessage');

        $this->channel->send(new TestNotifiable(), new TestNotification(['badge_id' => 'b1']));
    }

    #[Test]
    public function it_throws_when_no_badge_id_is_set(): void
    {
        $this->client->shouldNotReceive('issue');
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('badge ID');

        $this->channel->send(new TestNotifiable(), new TestNotification(new IssueBadgeMessage()));
    }

    #[Test]
    public function it_throws_when_no_recipient_name_can_be_resolved(): void
    {
        $this->client->shouldNotReceive('issue');
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('recipient name');

        $this->channel->send(new TestNotifiable(name: null), new TestNotification(IssueBadgeMessage::create('b1')));
    }

    #[Test]
    public function client_errors_bubble_up(): void
    {
        $this->client->shouldReceive('issue')->once()->andThrow(CouldNotSendNotification::serviceCommunicationError(new \RuntimeException('timeout')));
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('timeout');

        $this->channel->send(new TestNotifiable(), new TestNotification(IssueBadgeMessage::create('b1')));
    }
}
