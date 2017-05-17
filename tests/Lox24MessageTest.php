<?php

namespace NotificationChannels\Lox24\Test;

use NotificationChannels\Lox24\Exceptions\CouldNotSendNotification;
use NotificationChannels\Lox24\Lox24Message;

class Lox24MessageTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function constructorTest()
    {
        $testString = 'better test with simple characters';
        $message = new Lox24Message($testString);
        $this->assertInstanceOf(Lox24Message::class, $message);
        $arrayMessage = $message->toArray();

        $this->assertArrayHasKey('service', $arrayMessage);
        $this->assertArrayHasKey('text', $arrayMessage);
        $this->assertArrayHasKey('to', $arrayMessage);
        $this->assertArrayHasKey('encoding', $arrayMessage);
        $this->assertArrayHasKey('from', $arrayMessage);
        $this->assertArrayHasKey('timestamp', $arrayMessage);
        $this->assertArrayHasKey('return', $arrayMessage);
        $this->assertArrayHasKey('httphead', $arrayMessage);
        $this->assertArrayHasKey('action', $arrayMessage);

        $this->assertEquals($testString, $arrayMessage['text']);
    }

    /** @test */
    public function staticCreatorTest()
    {
        $testString = 'better test with simple characters';
        $message = Lox24Message::create($testString);
        $this->assertInstanceOf(Lox24Message::class, $message);
        $arrayMessage = $message->toArray();
        $this->assertEquals($testString, $arrayMessage['text']);
    }

    /** @test */
    public function setTextTest()
    {
        $testString = 'better test with simple characters';
        $message = new Lox24Message();
        $this->assertInstanceOf(Lox24Message::class, $message);
        $message->setText($testString);
        $arrayMessage = $message->toArray();
        $this->assertEquals($testString, $arrayMessage['text']);
    }


    /** @test */
    public function setToTest()
    {
        $phoneNumber = 'abcdefghijklmnoa';
        $message = new Lox24Message('message');
        $message->setTo($phoneNumber);
        $this->assertTrue($message->toExists());
        $arrayMessage = $message->toArray();
        $this->assertEquals($phoneNumber, $arrayMessage['to']);
    }


    /**
     * @test
     */
    public function setFromtest()
    {
        $from = 'sender';
        $message = new Lox24Message('message');
        $message->setFrom($from);
        $arrayMessage = $message->toArray();
        $this->assertEquals($from, $arrayMessage['from']);
    }


    /**
     * @test
     */
    public function setFromCanNotBeLongerThan11CharactersTest()
    {

        $this->setExpectedException(CouldNotSendNotification::class);
        $from = 'aaaaaaaaaaaa';
        $message = new Lox24Message('message');
        $message->setFrom($from);
    }

    /**
     * @test
     */
    public function setFromCanNotBeLongerThan15NumbersTest()
    {

        $this->setExpectedException(CouldNotSendNotification::class);
        $from = 1234567890123456;
        $message = new Lox24Message('message');
        $message->setFrom($from);
    }


    /**
     * @test
     */
    public function setSmsServiceProTest()
    {
        $message = new Lox24Message('message');
        $message->setSmsService(Lox24Message::LOX24_SERVICE_PRO);
        $arrayMessage = $message->toArray();
        $this->assertEquals(Lox24Message::LOX24_SERVICE_PRO, $arrayMessage['service']);
    }

    /**
     * @test
     */
    public function setSmsServiceEcoTest()
    {
        $message = new Lox24Message('message');
        $message->setSmsService(Lox24Message::LOX24_SERVICE_ECONOMY);
        $arrayMessage = $message->toArray();
        $this->assertEquals(Lox24Message::LOX24_SERVICE_ECONOMY, $arrayMessage['service']);
    }

    /**
     * @test
     */
    public function setSmsServiceDirectTest()
    {
        $message = new Lox24Message('message');
        $message->setSmsService(Lox24Message::LOX24_SERVICE_DIRECT);
        $arrayMessage = $message->toArray();
        $this->assertEquals(Lox24Message::LOX24_SERVICE_DIRECT, $arrayMessage['service']);
    }

    /**
     * @test
     */
    public function setSmsServiceThrowsOnWrongServiceNumberTest()
    {
        $this->setExpectedException(CouldNotSendNotification::class);
        $message = new Lox24Message('message');
        $message->setSmsService(1234);

    }


    /**
     * @test
     */
    public function testOnlyTest()
    {
        $message = new Lox24Message('message');
        $message->testOnly();
        $arrayMessage = $message->toArray();
        $this->assertEquals('info', $arrayMessage['action']);
    }


    /**
     * @test
     */
    public function sendAtTest()
    {
        $date = new \DateTime('january 2150');
        $message = new Lox24Message('message');
        $message->sendAt($date);
        $arrayMessage = $message->toArray();
        $this->assertEquals($date->getTimestamp(), $arrayMessage['timestamp']);
    }
}
