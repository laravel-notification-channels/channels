<?php

namespace NotificationChannels\Interfax\Test;

use Mockery;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{

    protected $testFiles = [
        'test-file.pdf',
        'test-file-1.pdf',
        'test-file-2.pdf',
    ];

    protected function getPackageProviders($app)
    {
        config([
            'services.interfax.username' => 'user',
            'services.interfax.password' => 'pass',
        ]);

        return [\NotificationChannels\Interfax\InterfaxServiceProvider::class];
    }

    public function setUp() : void
    {
        parent::setUp();

        $mpdf = new \Mpdf\Mpdf;
        $mpdf->WriteHTML('<h1>Test file contents</h1>');

        foreach($this->testFiles as $file)
            $mpdf->Output(app('filesystem')->path($file), \Mpdf\Output\Destination::FILE);
    }

    public function tearDown(): void
    {
        foreach($this->testFiles as $file)
            app('filesystem')->delete($file);

        parent::tearDown();

        if ($container = \Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }

        Mockery::close();
    }

    protected function addFile(string $filename) : void
    {
        $this->testFiles[] = $filename;
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
