<?php

namespace NotificationChannels\AwsSns\Test;

use Aws\Sns\SnsClient as SnsService;
use Illuminate\Contracts\Events\Dispatcher;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use NotificationChannels\AwsSns\Sns;
use NotificationChannels\AwsSns\SnsMessage;

class SnsTest extends MockeryTestCase
{
    /**
     * @var SnsService|Mockery\LegacyMockInterface|Mockery\MockInterface
     */
    protected $snsService;

    /**
     * @var Dispatcher|Mockery\LegacyMockInterface|Mockery\MockInterface
     */
    protected $dispatcher;

    /**
     * @var Sns
     */
    protected $sns;

    public function setUp(): void
    {
        parent::setUp();

        $this->snsService = Mockery::mock(SnsService::class);
        $this->dispatcher = Mockery::mock(Dispatcher::class);

        $this->sns = new Sns($this->snsService);
    }

    /** @test */
    public function it_can_send_a_promotional_sms_message_to_sns()
    {
        $message = new SnsMessage('Message text');

        $this->snsService->shouldReceive('publish')
            ->atLeast()->once()
            ->with([
                'Message' => 'Message text',
                'PhoneNumber' => '+1111111111',
                'MessageAttributes' => [
                    'AWS.SNS.SMS.SMSType' => [
                        'DataType' => 'String',
                        'StringValue' => 'Promotional',
                    ],
                ],
            ])
            ->andReturn(true);

        $this->sns->send($message, '+1111111111');
    }

    /** @test */
    public function it_can_send_a_transactional_sms_message_to_sns()
    {
        $message = new SnsMessage(['body' => 'Message text', 'transactional' => true]);

        $this->snsService->shouldReceive('publish')
            ->atLeast()->once()
            ->with([
                'Message' => 'Message text',
                'PhoneNumber' => '+22222222222',
                'MessageAttributes' => [
                    'AWS.SNS.SMS.SMSType' => [
                        'DataType' => 'String',
                        'StringValue' => 'Transactional',
                    ],
                ],
            ])
            ->andReturn(true);

        $this->sns->send($message, '+22222222222');
    }
}
