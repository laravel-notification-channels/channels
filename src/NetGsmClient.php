<?php

namespace NotificationChannels\NetGsm;

use Exception;
use GuzzleHttp\Client;
use NotificationChannels\NetGsm\Exceptions\CouldNotSendNotification;
use NotificationChannels\NetGsm\Exceptions\InvalidConfiguration;

class NetGsmClient
{
    /**
     * @var string
     */
    const URI = 'http://api.netgsm.com.tr/xmlbulkhttppost.asp';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $userCode;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $msgHeader;

    /**
     * @param  Client  $client
     * @param  string  $userCode
     * @param  string  $secret
     * @param  string  $msgHeader
     */
    public function __construct(Client $client, string $userCode, string $secret, string $msgHeader = '')
    {
        $this->client = $client;
        $this->userCode = $userCode;
        $this->secret = $secret;
        $this->msgHeader = $msgHeader;
    }

    /**
     * Send the Message.
     * @param  NetGsmMessage  $message
     * @return void
     * @throws Exception
     * @throws CouldNotSendNotification
     * @throws InvalidConfiguration
     */
    public function send(NetGsmMessage $message): void
    {
        if (empty($message->recipients)) {
            throw CouldNotSendNotification::emptyRecipients();
        }

        $msg =
            "<?xml version='1.0' encoding='utf-8'?>".
            '<mainbody>'.
            $this->prepareHeader($message).
            $this->prepareBody($message).
            '</mainbody>';

        try {
            $response = $this->client->post(self::URI, [
                'body' => $msg,
                'headers' => [
                    'Content-Type', 'text/xml; charset=utf-8',
                ],
            ]);
            $result = explode(' ', $response->getBody()->getContents());

            if (! isset($result[0])) {
                throw CouldNotSendNotification::invalidResponse();
            }

            if ($result[0] === '00' || $result[0] === '01' || $result[0] === '02') {
                return $result[1];
            } elseif ($result[0] === '20') {
                throw CouldNotSendNotification::invalidMessageContent();
            } elseif ($result[0] === '30') {
                throw InvalidConfiguration::invalidCredentials();
            } elseif ($result[0] === '40') {
                throw CouldNotSendNotification::invalidHeader();
            } elseif ($result[0] === '70') {
                throw CouldNotSendNotification::invalidRequest();
            } else {
                throw CouldNotSendNotification::unknownError();
            }
        } catch (InvalidConfiguration $exception) {
            throw $exception;
        } catch (CouldNotSendNotification $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        }
    }

    /**
     * @param  NetGsmMessage  $message
     * @return string
     */
    protected function prepareBody(NetGsmMessage $message): string
    {
        $recipients = implode("\n", array_map(function ($recipient) {
            return '<no>'.$recipient.'</no>';
        }, $message->recipients));

        return
            '<body>'.
            '<msg><![CDATA['.$message->body.']]></msg>'.
            $recipients.
            '</body>';
    }

    /**
     * @param  NetGsmMessage  $message
     * @return string
     */
    protected function prepareHeader(NetGsmMessage $message): string
    {
        return sprintf('<header>
                    <company dil=\'TR\'>NETGSM</company>
                    <usercode>%s</usercode>
                    <password>%s</password>
                    <startdate></startdate>
                    <stopdate></stopdate>
                    <type>1:n</type>
                    <msgheader>%s</msgheader>
                </header>', $this->userCode, $this->secret, $message->header ? $message->header : $this->msgHeader);
    }
}
