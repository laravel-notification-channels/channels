<?php
/**
 * Author: Hilmi Erdem KEREN
 * Date: 17/11/2016.
 */
namespace NotificationChannels\JetSMS\Clients\Http;

use GuzzleHttp\Client as GuzzleHttpClient;
use NotificationChannels\JetSMS\JetSMSMessageInterface;
use NotificationChannels\JetSMS\Exceptions\CouldNotBootClient;
use NotificationChannels\JetSMS\Clients\JetSMSClientInterface;
use NotificationChannels\JetSMS\Clients\JetSMSApiResponseInterface;
use NotificationChannels\JetSMS\Exceptions\CouldNotSendNotification;

/**
 * Class JetSMSClient.
 */
final class JetSMSClient implements JetSMSClientInterface
{
    /**
     * The end point.
     *
     * @var string
     */
    private static $endPoint;

    /**
     * The guzzle http client.
     *
     * @var GuzzleHttpClient
     */
    protected $httpClient;

    /**
     * The request parameters.
     *
     * @var array
     */
    protected $requestParams = [];

    /**
     * Initialize the dependencies.
     *
     * @param GuzzleHttpClient $client
     * @param string           $endpoint
     * @param string           $username
     * @param string           $password
     * @param string|null      $originator
     */
    public function __construct(GuzzleHttpClient $client, $endpoint, $username, $password, $originator = null)
    {
        $this->httpClient = $client;
        $this->boot($endpoint, $username, $password, $originator);
    }

    /**
     * Load the sms service specific configurations.
     *
     * @param  string $endpoint
     * @param  string $username
     * @param  string $password
     * @param  string $originator
     * @return void
     * @throws CouldNotBootClient
     */
    protected function boot($endpoint, $username, $password, $originator)
    {
        if (! $this->hasCredentials($endpoint, $username, $password)) {
            throw CouldNotBootClient::missingCredentials();
        }

        self::$endPoint = $endpoint;
        $this->requestParams['Username'] = $username;
        $this->requestParams['Password'] = $password;
        $this->requestParams['TransmissionID'] = $originator;
    }

    /**
     * Add an sms message to request.
     *
     * @param  JetSMSMessageInterface $smsMessage
     * @return void
     */
    public function addToRequest(JetSMSMessageInterface $smsMessage)
    {
        $smsParams = array_filter($smsMessage->toRequestParams());

        $this->requestParams = array_merge($this->requestParams, $smsParams);
    }

    /**
     * Send the client request to the service.
     *
     * @throws CouldNotSendNotification If SMS Api returns false.
     * @return JetSMSApiResponseInterface
     */
    public function sendRequest()
    {
        if (! $this->hasValidOriginator()) {
            throw CouldNotSendNotification::missingOriginator();
        }

        $guzzleResponse = $this->httpClient->request('POST', self::$endPoint, [
            'form_params' => $this->requestParams,
        ]);

        $response = new JetSMSHttpApiResponse((string) $guzzleResponse->getBody());

        if (! $response->isSuccess()) {
            $message = $response->errorMessage().'['.$response->errorCode().']';
            throw CouldNotSendNotification::apiFailed($message);
        }

        return $response;
    }

    /**
     * Validate the originator of the sms.
     *
     * @throws
     */
    private function hasValidOriginator()
    {
        return array_key_exists('TransmissionID', $this->requestParams) && (! is_null($this->requestParams['TransmissionID']));
    }

    /**
     * Validate the configuration provided api credentials.
     *
     * @param  string $endpoint
     * @param  string $username
     * @param  string $password
     * @throws CouldNotBootClient
     * @return bool
     */
    private function hasCredentials($endpoint, $username, $password)
    {
        return $endpoint != '' && $username != '' && $password != '';
    }
}
