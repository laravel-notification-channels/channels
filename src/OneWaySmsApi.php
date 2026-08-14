<?php

namespace NotificationChannels\OneWaySms;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use NotificationChannels\OneWaySms\Exceptions\CouldNotSendNotification;

class OneWaySmsApi
{
    public const DEFAULT_ENDPOINT = 'https://gateway.onewaysms.com.my:10001';

    public const MT_PATH = '/api.aspx';

    public const CREDIT_PATH = '/bulkcredit.aspx';

    public function __construct(
        protected HttpClient $client,
        protected string $endpoint = self::DEFAULT_ENDPOINT,
        protected ?string $username = null,
        protected ?string $password = null,
    ) {}

    /**
     * Send one MT request.
     *
     * @param  array{to: string, sender: string, languagetype: int, message: string}  $message
     * @return array<int, string> one MT ID per recipient
     *
     * @throws CouldNotSendNotification
     */
    public function send(array $message): array
    {
        $body = $this->request(self::MT_PATH, [
            'apiusername' => $this->username,
            'apipassword' => $this->password,
            'mobileno' => Arr::get($message, 'to'),
            'senderid' => Arr::get($message, 'sender'),
            'languagetype' => Arr::get($message, 'languagetype'),
            'message' => Arr::get($message, 'message'),
        ]);

        $ids = array_map('trim', explode(',', $body));

        if (! is_numeric($ids[0])) {
            throw CouldNotSendNotification::unexpectedResponse($body);
        }

        if ((float) $ids[0] < 0) {
            throw $this->sendError((int) $ids[0]);
        }

        return $ids;
    }

    /**
     * Read the account's SMS credit balance.
     *
     * A non-numeric body throws rather than casting to zero: silently reading a
     * gateway error page as "no credit" would be worse than failing loudly.
     *
     * @throws CouldNotSendNotification
     */
    public function checkBalance(): float
    {
        $body = $this->request(self::CREDIT_PATH, [
            'apiusername' => $this->username,
            'apipassword' => $this->password,
        ]);

        if (! is_numeric($body)) {
            throw CouldNotSendNotification::unexpectedResponse($body);
        }

        if ((float) $body < 0) {
            throw (int) $body === -100
                ? CouldNotSendNotification::invalidCredentials()
                : CouldNotSendNotification::unknownErrorCode((int) $body);
        }

        return (float) $body;
    }

    /**
     * Issue a request and return the trimmed plain-text body.
     *
     * @param  array<string, mixed>  $query
     *
     * @throws CouldNotSendNotification
     */
    protected function request(string $path, array $query): string
    {
        try {
            // DEFAULT_ENDPOINT's host and non-standard port are inferred, not
            // vendor-documented, so a blackholed TCP connect is the single
            // most likely first-run failure. Guzzle otherwise defaults to no
            // timeout at all (connect_timeout: 0, timeout: 0, i.e. cURL's own
            // 300s), which would hang the request - or a queue worker - for
            // minutes instead of failing fast. Set request-level rather than
            // client-level so this survives an application-bound HttpClient
            // without overriding that client's other configured defaults.
            $response = $this->client->request('GET', rtrim($this->endpoint, '/').$path, [
                'query' => $query,
                'connect_timeout' => 10,
                'timeout' => 30,
            ]);
        } catch (GuzzleException $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithOneWaySms($exception);
        }

        return trim((string) $response->getBody());
    }

    protected function sendError(int $code): CouldNotSendNotification
    {
        return match ($code) {
            -100 => CouldNotSendNotification::invalidCredentials(),
            -200 => CouldNotSendNotification::invalidSenderId(),
            -300 => CouldNotSendNotification::invalidRecipient(),
            -400 => CouldNotSendNotification::invalidLanguageType(),
            -500 => CouldNotSendNotification::invalidMessageCharacters(),
            -600 => CouldNotSendNotification::insufficientCredit(),
            default => CouldNotSendNotification::unknownErrorCode($code),
        };
    }
}
