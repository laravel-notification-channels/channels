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

        $ids = explode(',', $body);

        if (! is_numeric($ids[0])) {
            throw CouldNotSendNotification::unexpectedResponse($body);
        }

        if ((float) $ids[0] < 0) {
            throw $this->sendError((int) $ids[0]);
        }

        return $ids;
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
            $response = $this->client->request('GET', rtrim($this->endpoint, '/').$path, [
                'query' => $query,
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
