<?php

declare(strict_types=1);

namespace NotificationChannels\RuStore;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use NotificationChannels\RuStore\Exceptions\RuStorePushException;
use NotificationChannels\RuStore\Reports\RuStoreReport;
use NotificationChannels\RuStore\Reports\RuStoreSingleReport;
use Throwable;

class RuStoreClient
{
    // @todo задействовать проверку максимального объема сообщения
    public const MAX_PAYLOAD_LENGTH = 4096;

    private const URL_FORMAT = 'https://vkpns.rustore.ru/v1/projects/%s/messages:send';
    private readonly string $url;
    private readonly string $bearer_token;

    public function __construct()
    {
        $this->url = sprintf(self::URL_FORMAT, config('ru-store.project_id'));
        $this->bearer_token = config('ru-store.token');
    }

    /**
     * Отправка уведомлений на все устройства пользователя.
     *
     * @param  RuStoreMessage  $message
     * @param  array  $tokens
     * @return RuStoreReport
     */
    public function send(RuStoreMessage $message, array $tokens): RuStoreReport
    {
        $report = RuStoreReport::init($tokens, $message);
        $report->all()->each(function (?RuStoreSingleReport $_, string $token) use ($report, $message) {
            $report->addReport($token, $this->sendSingle($message, $token));
        });

        return $report;
    }

    /**
     * Отправка уведомления на конкретное устройство пользователя.
     *
     * @param  RuStoreMessage  $message
     * @param  string  $token
     * @return RuStoreSingleReport
     */
    public function sendSingle(RuStoreMessage $message, string $token): RuStoreSingleReport
    {
        try {
            $request = Http::withToken($this->bearer_token)->withBody($message->getPayload($token));
            /** @var PromiseInterface|Response $response */
            $response = $request->send('POST', $this->url);
        } catch (Throwable $exception) {
            return RuStoreSingleReport::failure($exception);
        }

        return $response->successful()
            ? RuStoreSingleReport::success($response)
            : RuStoreSingleReport::failure(RuStorePushException::fromResponse($response), $response);
    }
}
