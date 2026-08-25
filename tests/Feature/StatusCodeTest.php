<?php

declare(strict_types=1);

namespace NotificationChannels\RuStore\Test\Feature;

use Illuminate\Http\Client\RequestException;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Events\NotificationSending;
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
 * StatusCodeTest - проверка обработки некоторых статусов ответа с помощью Http::fake().
 */
class StatusCodeTest extends TestCase
{
    #[Test]
    #[TestDox('Проверка обработки успешного ответа')]
    public function handle_success_response200(): void
    {
        Event::fake();
        Http::fake([$this->url => Http::response()]);
        $notification = new TestNotification();
        $notifiable = new User();

        $notifiable->notify($notification);

        Event::assertDispatched(NotificationSending::class);
        Event::assertDispatched(NotificationSent::class);
        Event::assertNotDispatched(NotificationFailed::class);
    }

    #[Test]
    #[TestDox('Проверка обработки ошибочного ответа 301 Moved Permanently')]
    public function handle_error_response301(): void
    {
        Event::fake();
        Http::fake([
            $this->url => Http::response([
                'code' => 301,
                'message' => 'Moved Permanently',
                'status' => '',
            ], 301),
        ]);
        $notification = new TestNotification();
        $notifiable = new User();

        try {
            $notifiable->notify($notification);
        } catch (RuStorePushNotingSentException $e) {
        }

        $this::assertEquals(RuStorePushNotingSentException::class, $e::class);
        Event::assertDispatched(NotificationSending::class);
        Event::assertNotDispatched(NotificationSent::class);
        Event::assertDispatched(static function (NotificationFailed $event) {
            /** @var RequestException $e */
            $e = $event->data['report']->all()->sole()->error();

            return $e->getCode() === 301
                && $e->getMessage() === 'RuStoreRedirect: {"code":301,"message":"Moved Permanently","status":""}';
        });
    }

    #[Test]
    #[TestDox('Проверка обработки ошибочного ответа 401 Forbidden')]
    public function handle_error_response401(): void
    {
        Event::fake();
        Http::fake([
            $this->url => Http::response([
                'code' => 401,
                'message' => 'unauthorized: Invalid Authorization header',
                'status' => 'UNAUTHORIZED',
            ], 401),
        ]);
        $notification = new TestNotification();
        $notifiable = new User();

        try {
            $notifiable->notify($notification);
        } catch (RuStorePushNotingSentException $e) {
        }

        $this::assertEquals(RuStorePushNotingSentException::class, $e::class);
        Event::assertDispatched(NotificationSending::class);
        Event::assertNotDispatched(NotificationSent::class);
        Event::assertDispatched(static function (NotificationFailed $event) {
            /** @var RequestException $e */
            $e = $event->data['report']->all()->sole()->error();

            return $e->getCode() === 401
                && $e->getMessage() === 'RuStoreClientError: '
                .'{"code":401,"message":"unauthorized: Invalid Authorization header","status":"UNAUTHORIZED"}';
        });
    }

    #[Test]
    #[TestDox('Проверка обработки ошибочного ответа 403 Forbidden')]
    public function handle_error_response403(): void
    {
        Event::fake();
        Http::fake([
            $this->url => Http::response([
                'error' => [
                    'code' => 403,
                    'message' => 'SenderId mismatch',
                    'status' => 'PERMISSION_DENIED',
                ],
            ], 403),
        ]);
        $notification = new TestNotification();
        $notifiable = new User();

        try {
            $notifiable->notify($notification);
        } catch (RuStorePushNotingSentException $e) {
        }

        $this::assertEquals(RuStorePushNotingSentException::class, $e::class);
        Event::assertDispatched(NotificationSending::class);
        Event::assertNotDispatched(NotificationSent::class);
        Event::assertDispatched(static function (NotificationFailed $event) {
            /** @var RequestException $e */
            $e = $event->data['report']->all()->sole()->error();

            return $e->getCode() === 403 && $e->getMessage() === 'RuStoreClientError: '
                .'{"error":{"code":403,"message":"SenderId mismatch","status":"PERMISSION_DENIED"}}';
        });
    }

    #[Test]
    #[TestDox('Проверка обработки ошибочного ответа 404')]
    public function handle_error_response404(): void
    {
        Event::fake();
        Http::fake([
            $this->url => Http::response([
                'error' => [
                    'code' => 404,
                    'message' => 'Requested entity was not found.',
                    'status' => 'NOT_FOUND',
                ],
            ], 404),
        ]);
        $notification = new TestNotification();
        $notifiable = new User();

        try {
            $notifiable->notify($notification);
        } catch (RuStorePushNotingSentException $e) {
        }

        $this::assertEquals(RuStorePushNotingSentException::class, $e::class);
        Event::assertDispatched(NotificationSending::class);
        Event::assertNotDispatched(NotificationSent::class);
        Event::assertDispatched(static function (NotificationFailed $event) {
            /** @var RequestException $e */
            $e = $event->data['report']->all()->sole()->error();

            return $e->getCode() === 404 && $e->getMessage() === 'RuStoreClientError: '
                .'{"error":{"code":404,"message":"Requested entity was not found.","status":"NOT_FOUND"}}';
        });
    }

    #[Test]
    #[TestDox('Проверка обработки ошибочного ответа 500 Internal Server Error')]
    public function handle_error_response500(): void
    {
        Event::fake();
        Http::fake([
            $this->url => Http::response([
                'code' => 500,
                'message' => 'Internal Server Error',
                'status' => '',
            ], 500),
        ]);
        $notification = new TestNotification();
        $notifiable = new User();

        try {
            $notifiable->notify($notification);
        } catch (RuStorePushNotingSentException $e) {
        }

        $this::assertEquals(RuStorePushNotingSentException::class, $e::class);
        Event::assertDispatched(NotificationSending::class);
        Event::assertNotDispatched(NotificationSent::class);
        Event::assertDispatched(static function (NotificationFailed $event) {
            /** @var RequestException $e */
            $e = $event->data['report']->all()->sole()->error();

            return $e->getCode() === 500
                && $e->getMessage() === 'RuStoreServerError: {"code":500,"message":"Internal Server Error","status":""}';
        });
    }
}
