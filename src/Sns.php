<?php

namespace NotificationChannels\AwsSns;

use Aws\Exception\AwsException;
use Aws\Sns\SnsClient as SnsService;

class Sns
{
    /**
     * @var SnsService
     */
    protected $snsService;

    public function __construct(SnsService $snsService)
    {
        $this->snsService = $snsService;
    }

    /**
     * @param SnsMessage $message
     * @param $destination
     * @return \Aws\Result
     * @throws AwsException
     */
    public function send(SnsMessage $message, $destination)
    {
        $parameters = [
            'Message' => $message->getBody(),
            'PhoneNumber' => $destination,
            'MessageAttributes' => [
                'AWS.SNS.SMS.SMSType' => [
                    'DataType' => 'String',
                    'StringValue' => $message->getDeliveryType(),
                ],
            ],
        ];

        return $this->snsService->publish($parameters);
    }
}
