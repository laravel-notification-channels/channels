<?php

namespace NotificationChannels\Interfax\Test;

use Mockery;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        config([
            'services.interfax.username' => 'user',
            'services.interfax.password' => 'pass',
        ]);

        return [\NotificationChannels\Interfax\InterfaxServiceProvider::class];
    }

    public function tearDown(): void
    {
        parent::tearDown();

        if ($container = \Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }

        Mockery::close();
    }
}

class TestNotifiable
{
    use \Illuminate\Notifications\Notifiable;

    /**
     * @return string
     */
    public function routeNotificationForInterfax()
    {
        return '12345678901';
    }
}

class TestNotifiableNotSendable
{
    use \Illuminate\Notifications\Notifiable;

    /**
     * @return bool
     */
    public function routeNotificationForInterfax()
    {
        return false;
    }
}
