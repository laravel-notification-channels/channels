<?php
/**
 * Author: Hilmi Erdem KEREN
 * Date: 18/11/2016
 * Time: 00:39.
 */
namespace NotificationChannels\JetSMS\Test;

use Mockery as M;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Container\ContextualBindingBuilder;
use NotificationChannels\JetSMS\JetSMSServiceProvider;

class JetSMSServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    private $app;
    private $contextualBindingBuilder;

    public function setUp()
    {
        parent::setUp();

        $this->app = M::mock(Application::class);
        $this->contextualBindingBuilder = M::mock(ContextualBindingBuilder::class);
    }

    public function tearDown()
    {
        M::close();

        parent::tearDown();
    }

    /** @test */
    public function it_should_provide_services_on_boot()
    {
        $this->app->shouldReceive('when')
                  ->once()
                  ->andReturn($this->contextualBindingBuilder);
        $this->contextualBindingBuilder->shouldReceive('needs')
                                       ->once()
                                       ->andReturn($this->contextualBindingBuilder);
        $this->contextualBindingBuilder->shouldReceive('give')
                                       ->once();

        $provider = new JetSMSServiceProvider($this->app);

        $provider->boot();
    }
}
