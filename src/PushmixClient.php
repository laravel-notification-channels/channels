<?php

namespace NotificationChannels\Pushmix;

use GuzzleHttp\Client;
use NotificationChannels\Pushmix\Exceptions\InvalidConfiguration;


class PushmixClient
{
    protected $api_url = "https://www.pushmix.co.uk/api/notify";

    protected $client;
    protected $headers;
    protected $additionalParams;

    /**
     * @var bool
     */
    public $requestAsync = false;




    /**
     * Class Constructor
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->headers = ['headers' => []];
        $this->headers['headers']['Content-Type'] = 'application/json';
    }
    /***/

    /**
     * Turn on, turn off async requests
     *
     * @param bool $on
     * @return $this
     */
    public function async($on = true)
    {
        $this->requestAsync = $on;
        return $this;
    }

    /**
     * Get requestAsync
     * @return boolean
     */
    public function getRequestAsync(){
      return $this->requestAsync;
    }
    /***/

    /**
     * Initialize additional parameters
     */
    public function initKey(){

      if (is_null(config('services.pushmix.key', null))) {

          throw InvalidConfiguration::configurationNotSet();
      }

      $this->additionalParams = [

        'key_id'    => config('services.pushmix.key')
      ];
    }
    /***/

    /**
     * Get an array of additional parameters
     * @return array
     */
    public function getAdditionalParams(){
      return $this->additionalParams;
    }
    /***/

    /**
     * Get Subscription ID from config file
     *
     * @throw  InvalidConfiguration exception
     * @return string subscription id
     */
    public function getKey(){

      return $this->additionalParams['key_id'];
    }
    /***/

    /**
     * Set Subscription ID
     * @param string $key subscription id
     */
    public function setKey($key){

        $this->additionalParams = [
          'key_id'    => $key
        ];

    }
    /***/

    /**
     * Set API URL
     * @param string $url
     */
    public function setApiUrl($url){
      $this->api_url = $url;
    }
    /***/

    /**
     * Get API URL
     * @return string
     */
    public function getApiUrl(){
      return $this->api_url;
    }
    /***/

    /**
     * Merge Notification Parameters and Send Notification to Pushmix API
     *
     * @param array $parameters notification parameters
     * @return mixed
     */
    public function sendNotification($parameters){


        $parameters = array_merge($parameters, $this->additionalParams);
        $this->headers['body'] = json_encode($parameters);
        $this->headers['verify'] = false;
        return $this->post();
    }



    /**
     * POST Call to Pushmix API
     * @param  string $endPoint API endpoint
     * @return mixed
     */
    public function post() {

        if($this->requestAsync === true) {

            return $this->client->postAsync($this->api_url, $this->headers);

        }


        return $this->client->post($this->api_url, $this->headers);


    }
    /***/

}
