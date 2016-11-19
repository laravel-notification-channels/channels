<?php
/**
 * Author: Hilmi Erdem KEREN
 * Date: 17/11/2016.
 */
namespace NotificationChannels\JetSMS\Clients\Http;

use NotificationChannels\JetSMS\Clients\JetSMSApiResponseInterface;

/**
 * Class JetSMSHttpApiResponse.
 */
final class JetSMSHttpApiResponse implements JetSMSApiResponseInterface
{
    /**
     * The read response of SMS message request..
     *
     * @var array
     */
    private $responseAttributes = [];

    /**
     * The JetSMS error codes.
     *
     * @var array
     */
    private $errorCodes = [
        '-1'  => 'The specified SMS outbox name is invalid.',
        '-5'  => 'The SMS service credentials are incorrect.',
        '-6'  => 'The specified data is malformed.',
        '-7'  => 'The send date of the SMS message has already expired.',
        '-8'  => 'The SMS gsm number is invalid.',
        '-9'  => 'The SMS message body is missing.',
        '-15' => 'The SMS service is having some trouble at the moment.',
        '-99' => 'The SMS service encountered an unexpected error.',
    ];

    /**
     * Create a message response.
     *
     * @param  string $responseBody
     */
    public function __construct($responseBody)
    {
        $this->responseAttributes = $this->readResponseBodyString($responseBody);
    }

    /**
     * Get the error code of the JetSMS Api.
     *
     * @return int
     */
    public function errorCode()
    {
        return $this->responseAttributes['errorCode'];
    }

    /**
     * Get the error message og the JetSMS Api.
     *
     * @return null|string
     */
    public function errorMessage()
    {
        return $this->makeErrorMessage($this->errorCode());
    }

    /**
     * Determine if the api responded with a success or not.
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->responseAttributes['success'];
    }

    /**
     * Get the message report identifiers for the messages sent.
     * Message report id returns -1 if invalid Msisdns, -2 if invalid message text.
     *
     * @return array
     */
    public function messageReportIdentifiers()
    {
        if (array_key_exists('messageids', $this->responseAttributes)) {
            return explode('|', $this->responseAttributes['messageids']);
        }

        return [];
    }

    /**
     * If there is sth wrong, get the error message for the given status code.
     *
     * @param  string $statusCode
     * @return string
     */
    private function makeErrorMessage($statusCode)
    {
        if (array_key_exists($statusCode, $this->errorCodes)) {
            return $this->errorCodes[$statusCode];
        }

        return 'The SMS Service returned with an unexpected error.';
    }

    /**
     * Read the message response body string.
     *
     * @param $responseBodyString
     * @return array
     */
    private function readResponseBodyString($responseBodyString)
    {
        $responseLines = array_filter(explode("\r\n", $responseBodyString));

        $result = [];
        foreach ($responseLines as $responseLine) {
            $responseParts = explode('=', $responseLine);
            $result[strtolower($responseParts[0])] = $responseParts[1];
        }

        $status = (int) array_pull($result, 'status');
        $result['success'] = ($status >= 0) ? true : false;
        $result['errorCode'] = $status;

        return $result;
    }
}
