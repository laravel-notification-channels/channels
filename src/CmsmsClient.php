<?php

namespace NotificationChannels\Cmsms;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use NotificationChannels\Cmsms\Exceptions\CouldNotSendNotification;
use SimpleXMLElement;

class CmsmsClient
{
    const GATEWAY_URL = 'https://sgw01.cm.nl/gateway.ashx';

    /**
     * @var GuzzleClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $productToken;

    /**
     * @param GuzzleClient $client
     * @param string $productToken
     */
    public function __construct(GuzzleClient $client, $productToken)
    {
        $this->client = $client;
        $this->productToken = $productToken;
    }

    /**
     * @param CmsmsMessage $message
     * @throws CouldNotSendNotification
     */
    public function send(CmsmsMessage $message)
    {
        if (empty($message->originator)) {
            $message->setOriginator(config('services.cmsms.originator'));
        }

        $messageXml = $this->buildMessageXml($message);

        $response = $this->client->request('POST', static::GATEWAY_URL, [
            'body' => $messageXml,
            'headers' => [
                'Content-Type' => 'application/xml',
            ],
        ]);

        // API returns an empty string on success
        // On failure, only the error string is passed
        $body = $response->getBody()->getContents();
        if (!empty($body)) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($body);
        }
    }

    /**
     * @param CmsmsMessage $message
     * @return string
     */
    protected function buildMessageXml(CmsmsMessage $message)
    {
        $xml = new SimpleXMLElement('<MESSAGES/>');

        $authentication = $xml->addChild('AUTHENTICATION');
        $authentication->addChild('PRODUCTTOKEN', $this->productToken);

        $msg = $xml->addChild('MSG');
        foreach ($message->toXmlArray() as $name => $value) {
            $msg->addChild($name, $value);
        }

        return $xml->asXML();
    }
}
