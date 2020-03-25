<?php

namespace NotificationChannels;

class HangoutsChatMessage
{
    /**
     * The GET parameters of the request.
     *
     * @var array|string|null
     */
    protected $query;

    /**
     * The POST data of the Webhook request.
     *
     * @var mixed
     */
    protected $data;

    /**
     * The headers to send with the request.
     *
     * @var array|null
     */
    protected $headers;

    /**
     * The user agent header.
     *
     * @var string|null
     */
    protected $userAgent;

    /**
     * The Guzzle verify option.
     *
     * @var bool|string
     */
    protected $verify = false;

    /**
     * @param mixed $data
     */
    public function __construct($data = '')
    {
        $this->data = $data;
    }

    /**
     * @param mixed $data
     *
     * @return static
     */
    public static function create($data = '')
    {
        return new static($data);
    }

    /**
     * Set the Webhook parameters to be URL encoded.
     *
     * @param mixed $query
     *
     * @return $this
     */
    public function query($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Set the Webhook data to be JSON encoded.
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function data($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Add a Webhook request custom header.
     *
     * @param string $name
     * @param string $value
     *
     * @return $this
     */
    public function header($name, $value)
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * Set the Webhook request UserAgent.
     *
     * @param string $userAgent
     *
     * @return $this
     */
    public function userAgent($userAgent)
    {
        $this->headers['User-Agent'] = $userAgent;

        return $this;
    }

    /**
     * Indicate that the request should be verified.
     *
     * @return $this
     */
    public function verify($value = true)
    {
        $this->verify = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'query' => $this->query,
            'data' => $this->data,
            'headers' => $this->headers,
            'verify' => $this->verify,
        ];
    }
}
