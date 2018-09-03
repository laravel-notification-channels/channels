<?php

namespace NotificationChannels\ExpoPushNotifications\Test;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use NotificationChannels\ExpoPushNotifications\ExpoPushNotificationsServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            ExpoPushNotificationsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => $this->getTempDirectory().'/database.sqlite',
            'prefix' => '',
        ]);

        $app['config']->set('auth.providers.users.model', User::class);
    }

    protected function setUpDatabase()
    {
        $this->resetDatabase();

        $this->createExponentPushNotificationInterestsTable();
    }

    protected function resetDatabase()
    {
        file_put_contents($this->getTempDirectory().'/database.sqlite', null);
    }

    protected function createExponentPushNotificationInterestsTable()
    {
        include_once '__DIR__'.'/../migrations/create_exponent_push_notification_interests_table.php.stub';

        (new \CreateExponentPushNotificationInterestsTable())->up();
    }

    public function getTempDirectory(): string
    {
        return __DIR__.'/temp';
    }

    public function markTestAsPassed()
    {
        $this->assertTrue(true);
    }

    /**
     * Mocks a request for the ExpoController.
     *
     * @param $data
     *
     * @return \Mockery\MockInterface
     */
    public function mockRequest($data)
    {
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')->andReturn($data);

        return $request;
    }

    /**
     * @param bool $fails
     *
     * @return \Mockery\MockInterface
     */
    public function mockValidator(bool $fails)
    {
        $validator = \Mockery::mock(\Illuminate\Validation\Validator::class);

        $validation = \Mockery::mock(Factory::class);
        $validation->shouldReceive('make')->once()->andReturn($validator);

        $validator->shouldReceive('fails')->once()->andReturn($fails);

        Validator::swap($validation);

        return $validator;
    }
}

class User
{
    public function getKey()
    {
        return 1;
    }
}
