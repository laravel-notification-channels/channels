<?php

namespace NotificationChannels\AllMySms;

use GuzzleHttp\RequestOptions;
use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;

class AllMySms
{
    /**
     * The HTTP client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $http;

    /**
     * The service configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * Create a new AllMySms instance.
     *
     * @param  \GuzzleHttp\Client  $http
     * @param  array  $config
     */
    public function __construct(HttpClient $http, array $config)
    {
        $this->http = $http;
        $this->config = $config;
    }

    /**
     * Get HTTP client.
     *
     * @return \GuzzleHttp\Client
     */
    protected function httpClient(): HttpClient
    {
        return $this->http ?? new HttpClient();
    }

    /**
     * Send the sms.
     *
     * @param  string  $to
     * @param  array  $data
     * @param  string|null  $sender
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function sendSms(string $to, array $data, ?string $sender = null): ?ResponseInterface
    {
        $response = $this->httpClient()->post($this->getFullUrl('sendSms'), [
            RequestOptions::FORM_PARAMS => $this->formatRequest($to, $data, $sender),
        ]);

        return $response->getStatusCode() === 200
            ? $this->checkResponseAndReturn($response)
            : $response;
    }

    protected function checkResponseAndReturn(ResponseInterface $response): ResponseInterface
    {
        $content = json_decode($response->getBody()->getContents());

        $code = data_get($content, 'status');

        return $code === 100
            ? $response
            : $response->withStatus(400, data_get($content, 'statusText', 'An error occurred!'));
    }

    /**
     * Get the full url for the given path.
     *
     * @param  string  $path
     * @return string
     */
    protected function getFullUrl(string $path): string
    {
        return trim($this->config['uri'], '/').'/'.ltrim($path, '/');
    }

    /**
     * Get the form params.
     *
     * @param  string  $to
     * @param  array  $data
     * @param  string|null  $sender
     * @return array
     */
    protected function formatRequest(string $to, array $data, ?string $sender = null): array
    {
        $index = 1;
        $parameters = collect(data_get($data, 'parameters', []))->mapWithKeys(function ($item) use (&$index) {
            return ["PARAM_{$index}" => $item];
        })->toArray();

        $sms = [
            'DATA' => [
                'MESSAGE' => $data['message'],
                'SMS' => [
                    array_merge([
                        'MOBILEPHONE' => $to,
                    ], $parameters),
                ],
            ],
        ];

        if (! empty($parameters)) {
            $sms['DATA']['DYNAMIC'] = count($parameters);
        }

        if ($sender = data_get($data, 'sender', $sender)) {
            $sms['DATA']['TPOA'] = $sender;
        }

        if ($campaign = data_get($data, 'campaign')) {
            $sms['DATA']['CAMPAIGN_NAME'] = $campaign;
        }

        if ($date = data_get($data, 'date')) {
            $sms['DATA']['DATE'] = $date;
        }

        return [
            'login' => $this->config['login'],
            'apiKey' => $this->config['api_key'],
            'returnformat' => $this->config['format'],
            'smsData' => json_encode($sms),
        ];
    }
}
