<?php

namespace NotificationChannels\PersianSms\IPPanel; 

use Illuminate\Notifications\Notification;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use NotificationChannels\PersianSms\Exceptions\CouldNotSendNotification;
// use NotificationChannels\PersianSms\IPPanelMessage;

class IPPanelChannel
{
    /**
     * Base API URL for IPPanel.
     */
    protected const API_BASE_URL = 'https://api2.ippanel.com/api/v1';

    /**
     * Endpoint for sending a single normal SMS.
     */
    protected const ENDPOINT_SEND_SINGLE = '/sms/send/webservice/single';

    /**
     * Endpoint for sending a pattern-based SMS.
     */
    protected const ENDPOINT_SEND_PATTERN = '/sms/pattern/normal/send';

    /**
     * Endpoint for checking account credit.
     */
    protected const ENDPOINT_CHECK_CREDIT = '/sms/accounting/credit/show';

    /**
     * The HTTP client instance.
     */
    protected HttpClient $client;

    /**
     * The API key for IPPanel.
     */
    protected string $apiKey;

    /**
     * The default sender number (line number) for IPPanel.
     * Can be overridden by IPPanelMessage.
     */
    protected string $defaultSenderNumber;

    /**
     * Create a new IPPanel channel instance.
     *
     * @param HttpClient $client The Guzzle HTTP client instance.
     * @param string $apiKey IPPanel API Key.
     * @param string $defaultSenderNumber Default IPPanel sender number (line number).
     */
    public function __construct(HttpClient $client, string $apiKey, string $defaultSenderNumber)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->defaultSenderNumber = $defaultSenderNumber;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return \Psr\Http\Message\ResponseInterface|null
     * @throws \NotificationChannels\PersianSms\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        // Resolve the message from the notification first.
        $message = $notification->toPersianSms($notifiable);

        if (!$message instanceof IPPanelMessage) {
            if (is_string($message)) {
                throw CouldNotSendNotification::invalidMessageObject("Message must be an instance of IPPanelMessage. String given: " . $message);
            }
            throw CouldNotSendNotification::invalidMessageObject($message);
        }

        // Determine the recipient. Prioritize the one set on the message itself.
        $recipient = $message->recipient ?: $this->getRecipient($notifiable, $notification);

        if (!$recipient) {
            // No recipient found, do not proceed.
            return null;
        }

        // Ensure recipient is an array for IPPanel API
        $recipients = is_array($recipient) ? $recipient : [$recipient];

        $sender = $message->sender ?: $this->defaultSenderNumber; // Use message sender or default

        if (empty(trim($sender))) {
            throw CouldNotSendNotification::senderNotProvided();
        }

        $payload = [];
        $endpoint = '';

        if ($message->isPattern()) {
            // Sending with pattern
            if (empty($message->patternCode)) {
                throw CouldNotSendNotification::missingPatternCode();
            }
            if ($message->variables === null || !is_array($message->variables)) {
                throw CouldNotSendNotification::invalidPatternVariables();
            }

            $endpoint = self::API_BASE_URL . self::ENDPOINT_SEND_PATTERN;
            $payload = [
                'code'      => $message->patternCode,
                'sender'    => $sender,
                'recipient' => $recipients[0], // Pattern send seems to be for a single recipient based on docs
                'variable'  => (object) $message->variables, // Ensure it's an object (empty or with data)
            ];
        } else {
            // Sending normal SMS
            if (empty(trim((string) $message->content))) {
                throw CouldNotSendNotification::contentNotProvided();
            }
            $endpoint = self::API_BASE_URL . self::ENDPOINT_SEND_SINGLE;
            $payload = [
                'recipient' => $recipients,
                'sender'    => $sender,
                'message'   => (string) $message->content,
            ];
            // Optional: Add 'time' if IPPanelMessage supports it
            // if ($message->time) {
            //     $payload['time'] = $message->time; // Format: "2025-03-21T09:12:50.824Z"
            // }
        }

        try {
            $response = $this->client->post($endpoint, [
                'headers' => [
                    'apiKey' => $this->apiKey,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ],
                'json' => $payload, // Send data as JSON
            ]);

            $statusCode = $response->getStatusCode();
            $responseBody = json_decode($response->getBody()->getContents(), true);

            if ($statusCode >= 200 && $statusCode < 300 && isset($responseBody['status']) && $responseBody['status'] === 'OK') {
                return $response;
            }

            $errorMessage = $responseBody['errorMessage'] ?? 'Unknown error from IPPanel.';
            if (is_array($errorMessage)) { // Sometimes error messages are arrays
                $errorMessage = implode(', ', array_map(
                    function ($v, $k) { return sprintf("%s: %s", $k, implode('|', (array)$v)); },
                    $errorMessage,
                    array_keys($errorMessage)
                ));
            }
            throw CouldNotSendNotification::serviceRespondedWithAnError($errorMessage, $statusCode, $responseBody);

        } catch (RequestException $exception) {
            $response = $exception->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 503;
            $responseBody = $response ? json_decode($response->getBody()->getContents(), true) : null;
            $errorMessage = $responseBody['errorMessage'] ?? $exception->getMessage();
            if (is_array($errorMessage)) {
                $errorMessage = implode(', ', array_map(
                    function ($v, $k) { return sprintf("%s: %s", $k, implode('|',(array)$v)); },
                    $errorMessage,
                    array_keys($errorMessage)
                ));
            }
            throw CouldNotSendNotification::serviceRespondedWithAnError($errorMessage, $statusCode, $responseBody, $exception);
        } catch (\Exception $exception) {
            throw CouldNotSendNotification::genericError($exception->getMessage(), $exception);
        }
    }

    /**
     * Get the recipient's phone number(s).
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string|array|null
     */
    protected function getRecipient($notifiable, Notification $notification)
    {
        if ($route = $notifiable->routeNotificationFor('persianSms', $notification)) {
            return $route;
        }
        if ($route = $notifiable->routeNotificationFor(static::class, $notification)) {
            return $route;
        }
        if (method_exists($notifiable, 'routeNotificationForIPPanel')) {
            return $notifiable->routeNotificationForIPPanel($notification);
        }
        if (isset($notifiable->phone_number)) {
            return $notifiable->phone_number;
        }
        if (isset($notifiable->mobile)) {
            return $notifiable->mobile;
        }
        if (is_string($notifiable) || (is_array($notifiable) && count(array_filter($notifiable, 'is_string')) === count($notifiable))) {
            return $notifiable;
        }
        return null;
    }

    /**
     * (Optional) Method to check account credit.
     *
     * @return array|null Parsed JSON response from credit check or null on failure.
     * @throws CouldNotSendNotification
     */
    public function getCredit(): ?array
    {
        $endpoint = self::API_BASE_URL . self::ENDPOINT_CHECK_CREDIT;

        try {
            $response = $this->client->get($endpoint, [
                'headers' => [
                    'apiKey' => $this->apiKey,
                    'Accept'        => 'application/json',
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $responseBody = json_decode($response->getBody()->getContents(), true);

            if ($statusCode === 200 && isset($responseBody['status']) && $responseBody['status'] === 'OK') {
                return $responseBody['data'];
            }

            $errorMessage = $responseBody['errorMessage'] ?? 'Failed to retrieve credit information.';
            throw CouldNotSendNotification::serviceRespondedWithAnError($errorMessage, $statusCode, $responseBody);

        } catch (RequestException $exception) {
            $response = $exception->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 503;
            $responseBody = $response ? json_decode($response->getBody()->getContents(), true) : null;
            $errorMessage = $responseBody['errorMessage'] ?? $exception->getMessage();
            throw CouldNotSendNotification::serviceRespondedWithAnError($errorMessage, $statusCode, $responseBody, $exception);
        } catch (\Exception $exception) {
            throw CouldNotSendNotification::genericError("Failed to get credit: " . $exception->getMessage(), $exception);
        }
    }
}
