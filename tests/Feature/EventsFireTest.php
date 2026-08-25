<?php

declare(strict_types=1);

namespace NotificationChannels\RuStore\Test\Feature;

use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use NotificationChannels\RuStore\Exceptions\RuStorePushNotingSentException;
use NotificationChannels\RuStore\Test\Notifiable\User;
use NotificationChannels\RuStore\Test\Notifications\TestNotification;
use NotificationChannels\RuStore\Test\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * EventsFireTest - проверка поджигания событий NotificationSent и NotificationFailed.
 */
class EventsFireTest extends TestCase
{
    #[Test]
    #[TestDox('Успешная отправка уведомления на одно устройство. NotificationFailed не поджигается')]
    public function eventsFireOnOnlyOneSuccess(): void
    {
        Event::fake();
        $notification = new TestNotification();
        $notifiable = (new User())->setTokens(['valid']);
        Http::fakeSequence()->push(null, 200);

        $notifiable->notify($notification);

        Event::assertDispatched(static function (NotificationSent $event) {
            $tokens = $event->response->all()->keys()->toArray();

            return $tokens === ['valid'];
        });
        Event::assertNotDispatched(NotificationFailed::class);
    }

    #[Test]
    #[TestDox('Ошибочная отправка уведомления на одно устройство. NotificationSent поджигается, но response->reports пуст')]
    public function eventsFireOnOnlyOneFail(): void
    {
        Event::fake();
        $notification = new TestNotification();
        $notifiable = (new User())->setTokens(['invalid']);
        Http::fakeSequence()->push([
            'error' => [
                'code' => 404,
                'message' => 'Requested entity was not found.',
                'status' => 'NOT_FOUND',
            ],
        ], 404);

        try {
            $notifiable->notify($notification);
        } catch (RuStorePushNotingSentException $e) {
        }

        Event::assertDispatched(static function (NotificationFailed $event) {
            $tokens = $event->data['report']->all()->keys()->toArray();

            return $tokens === ['invalid'];
        });
    }

    #[Test]
    #[TestDox('Отправка уведомления на два устройства: отправка на первое вернула 200, на второе - 404')]
    public function eventsFireOnOneSuccessOneFail(): void
    {
        Event::fake();
        $notification = new TestNotification();
        $notifiable = (new User())->setTokens(['valid', 'invalid']);
        Http::fakeSequence()
            ->push(null, 200)
            ->push([
                'error' => [
                    'code' => 404,
                    'message' => 'Requested entity was not found.',
                    'status' => 'NOT_FOUND',
                ],
            ], 404);

        $notifiable->notify($notification);

        Event::assertDispatched(static function (NotificationSent $event) {
            $tokens = $event->response->all()->keys()->toArray();

            return $tokens === ['valid'];
        });
        Event::assertDispatched(static function (NotificationFailed $event) {
            $tokens = $event->data['report']->all()->keys()->toArray();

            return $tokens === ['invalid'];
        });
    }

    #[Test]
    #[TestDox('Отправка уведомления на четыре устройства: на два удачно, на два - неудачно')]
    public function eventsFireOnTwoSuccessTwoFail(): void
    {
        Event::fake();
        $notification = new TestNotification();
        $notifiable = (new User())->setTokens(['1_valid', '2_invalid', '3_valid', '4_invalid']);
        Http::fakeSequence()
            ->push(null, 200)
            ->push([
                'error' => [
                    'code' => 404,
                    'message' => 'Requested entity was not found.',
                    'status' => 'NOT_FOUND',
                ],
            ], 404)
            ->push(null, 200)
            ->push([
                'error' => [
                    'code' => 404,
                    'message' => 'Requested entity was not found.',
                    'status' => 'NOT_FOUND',
                ],
            ], 404);

        $notifiable->notify($notification);

        Event::assertDispatched(static function (NotificationSent $event) {
            $tokens = $event->response->all()->keys()->toArray();

            return $tokens === ['1_valid', '3_valid'];
        });
        Event::assertDispatched(static function (NotificationFailed $event) {
            $tokens = $event->data['report']->all()->keys()->toArray();

            return $tokens === ['2_invalid', '4_invalid'];
        });
    }
}
