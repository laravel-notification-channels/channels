<?php

namespace NotificationChannels\Bonga\Test;

use Mockery;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function tearDown(): void
    {
        parent::tearDown();

        if ($container = \Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }

        Mockery::close();
    }
}