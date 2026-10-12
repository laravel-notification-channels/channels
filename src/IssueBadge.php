<?php

namespace NotificationChannels\IssueBadge;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use NotificationChannels\IssueBadge\Exceptions\CouldNotSendNotification;

/**
 * Minimal client for the IssueBadge issuing API.
 */
class IssueBadge
{
    public const DEFAULT_BASE_URL = 'https://app.issuebadge.com';

    protected string $baseUrl;

    public function __construct(
        protected string $apiKey,
        ?string $baseUrl,
        protected ClientInterface $http
    ) {
        $this->baseUrl = rtrim($baseUrl ?: self::DEFAULT_BASE_URL, '/');
    }

    /**
     * Issue a badge / certificate. Returns the decoded API response
     * (success, IssueId, publicUrl, issue_date, expire_date).
     *
     * @throws CouldNotSendNotification
     */
    public function issue(array $payload): array
    {
        try {
            $response = $this->http->request('POST', $this->baseUrl.'/api/v1/issue/create', [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->apiKey,
                    'Accept' => 'application/json',
                    'User-Agent' => 'laravel-notification-channels/issuebadge',
                ],
                'json' => $payload,
            ]);
        } catch (RequestException $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception->getResponse(), $exception);
        } catch (GuzzleException $exception) {
            throw CouldNotSendNotification::serviceCommunicationError($exception);
        }

        $data = json_decode((string) $response->getBody(), true);

        if (! is_array($data) || ($data['success'] ?? false) !== true) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
        }

        return $data;
    }
}
