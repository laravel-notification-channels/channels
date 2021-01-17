<?php

/**
 * Main SMS Service
 */

namespace Osen\Bonga;

class Sms
{
    protected $client;
    protected $secret;
    protected $key;

    public function __construct($client, $secret, $key)
    {
        $this->client = $client;
        $this->secret = $secret;
        $this->key    = $key;
    }

    /**
     * Perform a GET request to the M-PESA Daraja API
     * @param String $endpoint Daraja API URL Endpoint
     * @param String $credentials Formated Auth credentials
     *
     * @return string/bool
     */
    public function remote_get($endpoint)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $endpoint);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        return curl_exec($curl);
    }

    /**
     * Perform a POST request to the M-PESA Daraja API
     * @param String $endpoint Daraja API URL Endpoint
     * @param Array $data Formated array of data to send
     *
     * @return string/bool
     */
    public function remote_post($endpoint, $data = array())
    {
        $curl        = curl_init();
        $data_string = json_encode($data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_URL, $endpoint);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                "Content-Type:application/json",
            )
        );

        return curl_exec($curl);
    }

    /**
     * @param String $apiClientID    API Client ID (Provided above)    Numeric    Mandatory
     * @param String $key    API Key (Provided above)    String    Mandatory
     * @param String $secret    API Secret (Provided above)    String    Mandatory
     * @param String $txtMessage    SMS message    String    Mandatory
     * @param String $MSISDN    Phone Number    Numeric    Mandatory
     * @param String $serviceID    Service ID (Provided above) - Default 1    Numeric    Optional
     */
    public function send($phone, $message, $service = 1, $callback = null)
    {
        $data = array(
            'apiClientID' => $this->client,
            'key'         => $this->key,
            'secret'      => $this->secret,
            'txtMessage'  => $message,
            'MSISDN'      => $phone,
            'serviceID'   => $service,
        );

        $endpoint = "https://app.bongasms.co.ke/api/send-sms-v1";

        $response = $this->remote_post($endpoint, $data);

        /**
         * status Status of the request (222 - success, 666 - error) Numeric Mandatory
         * status_message Description of status String Mandatory
         * unique_id Unique Message ID String Optional
         * credits SMS Credits Balance Numeric Optional
         */
        return is_null($callback) ? $response : call_user_func($callback, array($response));
    }

    /***
     * status Status of the request (222 - success, 666 - error) Numeric Mandatory
     * status_message Description of status String Mandatory
     * client_name Name of oganization as registered on Bonga String Mandatory
     * sms_credits SMS credits String Mandatory
     * sms_threshold Top up alert threshold String Mandatory
     * api_client_id Organization Bonga ID, assigned on registration String Mandatory
     */
    public function balance($callback = null)
    {
        $data = array(
            'apiClientID' => $this->client,
            'key'         => $this->key,
        );

        $endpoint = 'https://app.bongasms.co.ke/api/check-credits';

        $response = $this->remote_get($endpoint, $data);
        return is_null($callback) ? $response : call_user_func($callback, array($response));
    }

    /**
     * $unique_id SMS unique id sent back when send-sms was invoked String Mandatory
     */
    public function delivery($message, $callback = null)
    {
        $data = array(
            'apiClientID' => $this->client,
            'key'         => $this->key,
            'unique_id'   => $message,
        );

        $endpoint = 'https://app.bongasms.co.ke/api/fetch-delivery';

        /**
         * Name Desc Type Option
         * status Status of the request (222 - success, 666 - error) Numeric Mandatory
         * status_message Description of status String Mandatory
         * delivery_status SMS delivery status String Mandatory
         * delivery_status_desc SMS delivery status description String Mandatory
         */
        $response = $this->remote_get($endpoint, $data);
        return is_null($callback) ? $response : call_user_func($callback, array($response));
    }

    public function parse($payload, $callback = null)
    {
        $response = trim($payload);
        return is_null($callback) ? $response : call_user_func($callback, array($response));
    }
}
