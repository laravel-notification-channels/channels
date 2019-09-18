<?php

namespace NotificationChannels\ExpoPushNotifications\Test;

use ExponentPhpSDK\Expo;
use Illuminate\Http\Request;
use ExponentPhpSDK\ExpoRegistrar;
use Illuminate\Events\Dispatcher;
use ExponentPhpSDK\ExpoRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Factory;
use ExponentPhpSDK\Repositories\ExpoFileDriver;
use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use NotificationChannels\ExpoPushNotifications\Models\Interest;
use NotificationChannels\ExpoPushNotifications\Http\ExpoController;
use NotificationChannels\ExpoPushNotifications\Repositories\ExpoDatabaseDriver;

class ExpoControllerTest extends TestCase
{
    /**
     * @var ExpoController
     */
    protected $expoController;

    /**
     * Sets up the expo controller with the given expo channel.
     *
     * @param ExpoRepository $expoRepository
     *
     * @return array
     */
    protected function setupExpo(ExpoRepository $expoRepository)
    {
        $expoChannel = new ExpoChannel(new Expo(new ExpoRegistrar($expoRepository)), new Dispatcher);
        $expoController = new ExpoController($expoChannel);

        return [$expoController, $expoChannel];
    }

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();

        // We will fake an authenticated user
        Auth::shouldReceive('user')->andReturn(new User());
    }

    public function tearDown()
    {
        \Mockery::close();

        parent::tearDown();
    }

    /**
     * Data provider to help test the expo controller with the different repositories.
     *
     * @return array
     */
    public function availableRepositories()
    {
        return [
            [new ExpoDatabaseDriver],
            [new ExpoFileDriver],
        ];
    }

    /** @test
     *
     * @param $expoRepository
     *
     * @dataProvider availableRepositories
     */
    public function aDeviceCanSubscribeToTheSystem($expoRepository)
    {
        [$expoController, $expoChannel] = $this->setupExpo($expoRepository);

        // We will fake a request with the following data
        $data = ['expo_token' => 'ExponentPushToken[fakeToken]'];
        $request = $this->mockRequest($data);
        $request->shouldReceive('get')->with('expo_token')->andReturn($data['expo_token']);

        $this->mockValidator(false);

        /** @var Request $request */
        $response = $expoController->subscribe($request);
        $response = json_decode($response->content());

        // The response should contain a succeeded status
        $this->assertEquals('succeeded', $response->status);
        // The response should return the registered token
        $this->assertEquals($data['expo_token'], $response->expo_token);

        if ($expoRepository instanceof ExpoDatabaseDriver) {
            $this->assertDatabaseHas(config('exponent-push-notifications.interests.database.table_name'), [
                'key' => 'NotificationChannels.ExpoPushNotifications.Test.User.'.(new User)->getKey(),
                'value' => $data['expo_token'],
            ]);
        }
    }

    /** @test
     *
     * @param $expoRepository
     *
     * @dataProvider availableRepositories
     */
    public function subscribeReturnsErrorResponseIfTokenInvalid($expoRepository)
    {
        [$expoController, $expoChannel] = $this->setupExpo($expoRepository);

        // We will fake a request with no data
        $request = $this->mockRequest([]);

        $this->mockValidator(true);

        /** @var Request $request */
        $response = $expoController->subscribe($request);

        // The response should contain a failed status
        $this->assertEquals('failed', json_decode($response->content())->status);
        // The response status should be 422
        $this->assertEquals(422, $response->getStatusCode());
    }

    /** @test */
    public function subscribeReturnsErrorResponseIfExceptionIsThrown()
    {
        // We will fake a request with the following data
        $data = ['expo_token' => 'ExponentPushToken[fakeToken]'];
        $request = $this->mockRequest($data);
        $request->shouldReceive('get')->andReturn($data['expo_token']);

        $this->mockValidator(false);

        $expo = \Mockery::mock(Expo::class);
        $expo->shouldReceive('subscribe')->andThrow(\Exception::class);

        /** @var Expo $expo */
        $expoChannel = new ExpoChannel($expo, new Dispatcher());

        /** @var Request $request */
        $response = (new ExpoController($expoChannel))->subscribe($request);
        $response = json_decode($response->content());

        $this->assertEquals('failed', $response->status);
    }

    /** @test
     *
     *
     * @dataProvider availableRepositories
     *
     * @param $expoRepository
     */
    public function aDeviceCanUnsubscribeSingleTokenFromTheSystem($expoRepository)
    {
        [$expoController, $expoChannel] = $this->setupExpo($expoRepository);

        // We will fake a request with the following data
        $data = ['expo_token' => 'ExponentPushToken[fakeToken]'];
        $request = $this->mockRequest($data);
        $request->shouldReceive('get')->with('expo_token')->andReturn($data['expo_token']);

        $this->mockValidator(false);

        // We will subscribe an interest to the server.
        $token = 'ExponentPushToken[fakeToken]';
        $interest = $expoChannel->interestName(new User());
        $expoChannel->expo->subscribe($interest, $token);

        $response = $expoController->unsubscribe($request);
        $response = json_decode($response->content());

        // The response should contain a deleted property with value true
        $this->assertTrue($response->deleted);

        if ($expoRepository instanceof ExpoDatabaseDriver) {
            $this->assertDatabaseMissing(config('exponent-push-notifications.interests.database.table_name'), [
                'key' => 'NotificationChannels.ExpoPushNotifications.Test.User.'.(new User)->getKey(),
                'value' => $data['expo_token'],
            ]);
        }
    }

    /** @test
     *
     * @param $expoRepository
     *
     * @dataProvider availableRepositories
     */
    public function aDeviceCanUnsubscribeFromTheSystem($expoRepository)
    {
        [$expoController, $expoChannel] = $this->setupExpo($expoRepository);

        // We will fake a request with the following data
        $request = $this->mockRequest([]);
        $request->shouldReceive('get')->with('expo_token')->andReturn([]);

        $this->mockValidator(false);

        // We will subscribe an interest to the server.
        $token = 'ExponentPushToken[fakeToken]';
        $interest = $expoChannel->interestName(new User());
        $expoChannel->expo->subscribe($interest, $token);

        $response = $expoController->unsubscribe($request);
        $response = json_decode($response->content());

        // The response should contain a deleted property with value true
        $this->assertTrue($response->deleted);

        if ($expoRepository instanceof ExpoDatabaseDriver) {
            $this->assertEquals(0, Interest::count());
        }
    }

    /** @test */
    public function unsubscribeReturnsErrorResponseIfExceptionIsThrown()
    {
        $request = $this->mockRequest([]);
        $request->shouldReceive('get')->with('expo_token')->andReturn([]);

        $expo = \Mockery::mock(Expo::class);
        $expo->shouldReceive('unsubscribe')->andThrow(\Exception::class);

        /** @var Expo $expo */
        $response = (new ExpoController(new ExpoChannel($expo, new Dispatcher())))->unsubscribe($request);
        $response = json_decode($response->content());

        $this->assertEquals('failed', $response->status);
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
