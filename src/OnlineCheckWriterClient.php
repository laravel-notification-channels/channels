<?php

namespace Zilmoney\OnlineCheckWriter;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Str;
use Zilmoney\OnlineCheckWriter\Exceptions\OnlineCheckWriterException;
use Zilmoney\OnlineCheckWriter\Message\OnlineCheckWriterDocumentMail;
use Zilmoney\OnlineCheckWriter\Message\OnlineCheckWriterMailCheck;
use Zilmoney\OnlineCheckWriter\Message\OnlineCheckWriterMessage;

class OnlineCheckWriterClient
{
    protected Client $client;
    protected Client $multipartClient;

    public function __construct(
        protected string $apiKey,
        protected string $baseUrl = 'https://api.onlinecheckwriter.com/api/v3',
        protected int $timeout = 30
    ) {
        $this->client = new Client([
            'base_uri' => rtrim($this->baseUrl, '/') . '/',
            'timeout' => $this->timeout,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);

        $this->multipartClient = new Client([
            'base_uri' => rtrim($this->baseUrl, '/') . '/',
            'timeout' => $this->timeout,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ],
        ]);
    }

    /**
     * Send a message (document mail or mail check) via OnlineCheckWriter API.
     *
     * @throws OnlineCheckWriterException
     */
    public function send(OnlineCheckWriterMessage $message): array
    {
        return match (true) {
            $message instanceof OnlineCheckWriterDocumentMail => $this->sendDocumentMail($message),
            $message instanceof OnlineCheckWriterMailCheck => $this->sendMailCheck($message),
            default => throw new OnlineCheckWriterException('Unknown message type'),
        };
    }

    /**
     * Send a document mail via the API.
     * This handles the two-step process: upload document, then mail it.
     *
     * @throws OnlineCheckWriterException
     */
    public function sendDocumentMail(OnlineCheckWriterDocumentMail $documentMail): array
    {
        $attachmentUrl = $documentMail->getAttachmentUrl();

        // If we have a file path but no attachment URL, upload the document first
        if (!$attachmentUrl && $documentMail->getFilePath()) {
            $uploadResponse = $this->uploadDocumentForMailing(
                $documentMail->getFilePath(),
                $documentMail->getDocumentTitle()
            );

            // Extract the attachment URL from upload response
            $attachmentUrl = $uploadResponse['data']['file_url'] ?? null;

            if (!$attachmentUrl) {
                throw new OnlineCheckWriterException(
                    'Failed to get attachment URL from upload response: ' . json_encode($uploadResponse)
                );
            }
        }

        if (!$attachmentUrl) {
            throw new OnlineCheckWriterException(
                'No attachment URL or file path provided for document mail'
            );
        }

        // Now send the mail PDF request
        return $this->mailPdf($documentMail->toArray($attachmentUrl));
    }

    /**
     * Upload a document for mailing.
     *
     * @throws OnlineCheckWriterException
     */
    public function uploadDocumentForMailing(string $filePath, string $documentTitle = null, string $idempotencyKey = null): array
    {
        try {
            $multipart = [
                [
                    'name' => 'document_title',
                    'contents' => $documentTitle ?? basename($filePath, '.pdf'),
                ],
                [
                    'name' => 'file',
                    'contents' => fopen($filePath, 'r'),
                    'filename' => basename($filePath),
                ],
                [
                    'name' => 'idempotency_key',
                    'contents' => $idempotencyKey ?? Str::uuid()->toString(),
                ],
            ];

            $response = $this->multipartClient->post('documentmailing/upload', [
                'multipart' => $multipart,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            $this->handleGuzzleException($e, 'upload document for mailing');
        }
    }

    /**
     * Send a mail check via the API.
     * This creates and mails a check in a single API call.
     *
     * @throws OnlineCheckWriterException
     */
    public function sendMailCheck(OnlineCheckWriterMailCheck $mailCheck): array
    {
        try {
            $response = $this->client->post('quickpay/mailcheck', [
                RequestOptions::JSON => $mailCheck->toArray(),
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            $this->handleGuzzleException($e, 'send mail check');
        }
    }

    /**
     * Mail a PDF document.
     *
     * @throws OnlineCheckWriterException
     */
    public function mailPdf(array $data): array
    {
        try {
            $response = $this->client->post('quickpay/mailpdf', [
                RequestOptions::JSON => $data,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            $this->handleGuzzleException($e, 'mail PDF');
        }
    }

    /**
     * Upload a document/attachment (legacy method).
     *
     * @throws OnlineCheckWriterException
     */
    public function uploadDocument(string $filePath, string $fileName = null): array
    {
        return $this->uploadDocumentForMailing($filePath, $fileName);
    }

    /**
     * Get the status of a mailed item.
     *
     * @throws OnlineCheckWriterException
     */
    public function getStatus(string $type, string $id): array
    {
        try {
            $response = $this->client->get("{$type}/{$id}");

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            $this->handleGuzzleException($e, 'get status');
        }
    }

    /**
     * Cancel a pending mail item.
     *
     * @throws OnlineCheckWriterException
     */
    public function cancel(string $type, string $id): array
    {
        try {
            $response = $this->client->delete("{$type}/{$id}");

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            $this->handleGuzzleException($e, 'cancel');
        }
    }

    /**
     * Verify an address.
     *
     * @throws OnlineCheckWriterException
     */
    public function verifyAddress(array $address): array
    {
        try {
            $response = $this->client->post('addresses/verify', [
                RequestOptions::JSON => $address,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            $this->handleGuzzleException($e, 'verify address');
        }
    }

    /**
     * Handle Guzzle exceptions and convert to OnlineCheckWriterException.
     *
     * @throws OnlineCheckWriterException
     */
    protected function handleGuzzleException(GuzzleException $e, string $action): never
    {
        $message = "Failed to {$action}: {$e->getMessage()}";
        $code = $e->getCode();
        $response = null;

        if (method_exists($e, 'getResponse') && $e->getResponse()) {
            $body = $e->getResponse()->getBody()->getContents();
            $response = json_decode($body, true);
            if ($response && isset($response['message'])) {
                $message = "Failed to {$action}: {$response['message']}";
            }
        }

        $exception = new OnlineCheckWriterException($message, $code, $e);
        if ($response) {
            $exception = OnlineCheckWriterException::withResponse($message, $response, $code);
        }

        throw $exception;
    }

    /**
     * Get the underlying Guzzle client.
     */
    public function getHttpClient(): Client
    {
        return $this->client;
    }
}
