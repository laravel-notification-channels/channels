<?php

namespace NotificationChannels\PusherApiNotifications\Test;

use Orchestra\Testbench\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use NotificationChannels\PusherApiNotifications\PusherApiMessage;

class MessageTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        $this->setUpFaker();

        parent::setUp();
    }

    /** @test */
    public function it_accepts_attributes_when_constructing_a_message()
    {
        $attributes = [
            'channels' => $this->faker->uuid,
            'event' => $this->faker->word,
            'data' => $this->faker->sentence,
            'socketId' => $this->faker->md5,
            'debug' => $this->faker->boolean,
            'alreadyEncoded' => $this->faker->boolean,
        ];

        $message = new PusherApiMessage(
            $attributes['channels'],
            $attributes['event'],
            $attributes['data'],
            $attributes['socketId'],
            $attributes['debug'],
            $attributes['alreadyEncoded']
        );

        $this->assertEquals($attributes, $message->toArray());
    }

    /** @test */
    public function it_accepts_attributes_one_by_one()
    {
        $attributes = [
            'channels' => $this->faker->uuid,
            'event' => $this->faker->word,
            'data' => $this->faker->sentence,
            'socketId' => $this->faker->md5,
            'debug' => $this->faker->boolean,
            'alreadyEncoded' => $this->faker->boolean,
        ];

        $message = (new PusherApiMessage)
            ->channels($attributes['channels'])
            ->event($attributes['event'])
            ->data($attributes['data'])
            ->socketId($attributes['socketId'])
            ->debug($attributes['debug'])
            ->alreadyEncoded($attributes['alreadyEncoded']);

        $this->assertEquals($attributes, $message->toArray());
    }
}
