<?php

namespace FtwSoft\NotificationChannels\Intercom\Tests;

use Illuminate\Contracts\Foundation\Application;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use FtwSoft\NotificationChannels\Intercom\IntercomChannel;
use FtwSoft\NotificationChannels\Intercom\IntercomServiceProvider;
use FtwSoft\NotificationChannels\Intercom\Tests\Mocks\TestFakeApplication;
use FtwSoft\NotificationChannels\Intercom\Tests\Mocks\TestConfigRepository;

class IntercomServiceProviderTest extends TestCase
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var IntercomServiceProvider
     */
    private $serviceProvider;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->app = new TestFakeApplication();
        $this->app['config'] = new TestConfigRepository();
        Config::setFacadeApplication($this->app);
        $this->serviceProvider = new IntercomServiceProvider($this->app);

        Notification::swap(new ChannelManager($this->app));

        $this->serviceProvider->boot();
    }

    public function testItBootsAndProvidesDIForIntercomClientFromConfig(): void
    {
        Config::set('services.intercom.token', 'SOME_TOKEN');
        /** @var IntercomChannel $client */
        $client = $this->app->make(IntercomChannel::class);

        self::assertEquals('SOME_TOKEN', $client->getClient()->getAuth()[0]);
    }

    public function testItRegistersNewIntercomNorificationDriverAlias(): void
    {
        $this->serviceProvider->register();

        self::assertInstanceOf(IntercomChannel::class, Notification::driver('intercom'));
    }
}
