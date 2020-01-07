<?php

namespace NotificationChannels\Interfax\Test;

use NotificationChannels\Interfax\Exceptions\CouldNotSendNotification;
use NotificationChannels\Interfax\InterfaxMessage;

class CouldNotSendNotificationExceptionTest extends TestCase
{
    protected $message;

    public function setUp(): void
    {
        parent::setUp();
        $this->message = (new InterfaxMessage)
                            ->checkStatus()
                            ->user(new TestNotifiable)
                            ->file('test-file.pdf');
    }

    /** @test */
    public function it_can_get_the_exception_user()
    {
        $exception = CouldNotSendNotification::serviceRespondedWithAnError($this->message, [
            'status' => 500,
            'message' => 'Failed to send.',
        ]);

        $this->assertInstanceOf(TestNotifiable::class, $exception->getUser());
    }

    /** @test */
    public function it_can_get_the_exception_attributes()
    {
        $exception = CouldNotSendNotification::serviceRespondedWithAnError($this->message, [
            'status' => 500,
            'message' => 'Failed to send.',
        ]);

        $this->assertSame([
            'status' => 500,
            'message' => 'Failed to send.',
        ], $exception->getAttributes());
    }
}
