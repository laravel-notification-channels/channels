<?php

namespace NotificationChannels\IssueBadge\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use NotificationChannels\IssueBadge\Exceptions\CouldNotSendNotification;
use NotificationChannels\IssueBadge\IssueBadge;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class IssueBadgeClientTest extends TestCase
{
    protected array $history = [];

    protected function client(array $queue, ?string $baseUrl = null): IssueBadge
    {
        $this->history = [];
        $stack = HandlerStack::create(new MockHandler($queue));
        $stack->push(Middleware::history($this->history));

        return new IssueBadge('secret-key', $baseUrl, new Client(['handler' => $stack]));
    }

    protected function lastRequest(): Request
    {
        return $this->history[0]['request'];
    }

    #[Test]
    public function it_posts_the_payload_with_a_bearer_token(): void
    {
        $client = $this->client([new Response(202, [], json_encode(['success' => true, 'IssueId' => 'i1', 'publicUrl' => 'https://x/issue/view/i1']))]);

        $response = $client->issue(['badge_id' => 'b1', 'name' => 'Ada']);

        $this->assertSame('i1', $response['IssueId']);
        $request = $this->lastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('https://app.issuebadge.com/api/v1/issue/create', (string) $request->getUri());
        $this->assertSame('Bearer secret-key', $request->getHeaderLine('Authorization'));
        $this->assertSame('application/json', $request->getHeaderLine('Accept'));
        $this->assertSame(['badge_id' => 'b1', 'name' => 'Ada'], json_decode((string) $request->getBody(), true));
    }

    #[Test]
    public function it_honours_a_custom_base_url(): void
    {
        $client = $this->client([new Response(202, [], json_encode(['success' => true]))], 'https://issuebadge.test/');

        $client->issue(['badge_id' => 'b1', 'name' => 'Ada']);

        $this->assertSame('https://issuebadge.test/api/v1/issue/create', (string) $this->lastRequest()->getUri());
    }

    #[Test]
    public function validation_errors_are_reported_with_field_details(): void
    {
        $client = $this->client([new Response(422, [], json_encode([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => ['badge_id' => ['The badge id field is required.']],
        ]))]);

        try {
            $client->issue(['name' => 'Ada']);
            $this->fail('Expected CouldNotSendNotification');
        } catch (CouldNotSendNotification $exception) {
            $this->assertSame(422, $exception->getCode());
            $this->assertStringContainsString('HTTP 422', $exception->getMessage());
            $this->assertStringContainsString('badge_id: The badge id field is required.', $exception->getMessage());
        }
    }

    #[Test]
    public function an_invalid_key_is_reported(): void
    {
        $client = $this->client([new Response(401, [], json_encode(['success' => false, 'message' => 'Invalid API key or OAuth2 token']))]);

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('HTTP 401: Invalid API key');

        $client->issue(['badge_id' => 'b1', 'name' => 'Ada']);
    }

    #[Test]
    public function a_success_false_body_is_an_error(): void
    {
        $client = $this->client([new Response(200, [], json_encode(['success' => false, 'message' => 'Plan limit reached']))]);

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('Plan limit reached');

        $client->issue(['badge_id' => 'b1', 'name' => 'Ada']);
    }

    #[Test]
    public function a_non_json_body_is_an_error(): void
    {
        $client = $this->client([new Response(200, [], '<html>maintenance</html>')]);

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('unknown error');

        $client->issue(['badge_id' => 'b1', 'name' => 'Ada']);
    }

    #[Test]
    public function connection_failures_are_wrapped(): void
    {
        $client = $this->client([new ConnectException('Connection refused', new Request('POST', '/'))]);

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('Could not reach IssueBadge: Connection refused');

        $client->issue(['badge_id' => 'b1', 'name' => 'Ada']);
    }
}
