<?php

namespace NotificationChannels\Webex;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\RequestOptions;
use Illuminate\Notifications\Notification;
use NotificationChannels\Webex\Exceptions\CouldNotSendNotification;

class WebexChannel
{
    /**
     * The HTTP client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $http;

    /**
     * Webex HTTP API URL.
     *
     * @var string
     */
    protected $url;

    /**
     * The sender's Webex ID.
     *
     * @var string
     */
    protected $id;

    /**
     * The sender's Webex Access Token.
     *
     * @var string
     */
    protected $token;

    public function __construct(HttpClient $http, string $url, string $id, string $token)
    {
        $this->http = $http;
        $this->url = $url;
        $this->id = $id;
        $this->token = $token;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return \Psr\Http\Message\ResponseInterface|void
     *
     * @throws Exceptions\CouldNotCreateNotification at least one of {@see WebexMessage} instance
     *                                               properties is missing or invalid
     * @throws Exceptions\CouldNotSendNotification the Webex service configuration is missing,
     *                                             the Webex API can't be reached or responds
     *                                             with a client/server error
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $recipient = $notifiable->routeNotificationFor('webex', $notification)) {
            return;
        }

        if (empty($this->url) || empty($this->id) || empty($this->token)) {
            throw CouldNotSendNotification::missingConfiguration();
        }

        /** @var WebexMessage $message */
        $message = $notification->toWebex($notifiable);

        if (! isset($message->toPersonEmail) && ! isset($message->toPersonId) &&
            ! isset($message->roomId)) {
            $message->to($recipient);
        }

        $options = function (WebexMessage $message) {
            $arr = [
                RequestOptions::HEADERS => ['Authorization' => 'Bearer '.$this->token],
            ];
            isset($message->files) ?
                $arr[RequestOptions::MULTIPART] = $message->toArray() :
                $arr[RequestOptions::JSON] = $message->jsonSerialize();

            return $arr;
        };

        try {
            $response = $this->http->request('POST', $this->url, $options($message));
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::webexRespondedWithClientError($exception);
        } catch (ServerException $exception) {
            throw CouldNotSendNotification::webexRespondedWithServerError($exception);
        } catch (GuzzleException $exception) {
            throw CouldNotSendNotification::communicationError($exception);
        }

        return $response;
    }
}
