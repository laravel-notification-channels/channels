<?php

declare(strict_types=1);

namespace NotificationChannels\RuStore\Reports;

use Illuminate\Support\Collection;
use NotificationChannels\RuStore\Exceptions\RuStorePushNotingSentException;
use NotificationChannels\RuStore\RuStoreMessage;

/**
 * Отчет об отправке уведомлений на все устройства пользователя.
 */
final class RuStoreReport
{
    /**
     * @param  Collection  $reports  Коллекция отчетов об отправке уведомлений с push-токенами в качестве ключей
     * @param  RuStoreMessage  $message  Отправляемое сообщение
     */
    public function __construct(
        private Collection $reports,
        readonly private RuStoreMessage $message,
    ) {
    }

    /**
     * Инициализация объекта.
     *
     * @param  array  $tokens
     * @param  RuStoreMessage  $message
     * @return self
     */
    public static function init(array $tokens, RuStoreMessage $message): self
    {
        return new self(
            reports: collect(array_combine($tokens, array_fill(0, count($tokens), null))),
            message: $message,
        );
    }

    /**
     * Получение коллекции отчетов.
     *
     * @return Collection<RuStoreSingleReport>
     */
    public function all(): Collection
    {
        return $this->reports;
    }

    /**
     * Добавление отчета об отправке уведомления адресату $token.
     *
     * @param  string  $token
     * @param  RuStoreSingleReport  $report
     * @return self
     */
    public function addReport(string $token, RuStoreSingleReport $report): self
    {
        $this->reports->put($token, $report);

        return $this;
    }

    /**
     * Получение отчета об успешных отправках.
     *
     * @return RuStoreReport
     *
     * @throws RuStorePushNotingSentException
     */
    public function getSuccess(): self
    {
        $success = clone $this;
        $success->reports = $this->reports->filter(fn (RuStoreSingleReport $report) => $report->isSuccess());

        if ($success->reports->count() === 0) {
            throw new RuStorePushNotingSentException();
        }

        return $success;
    }

    /**
     * Получение отчета об ошибочных отправках.
     *
     * @return RuStoreReport
     */
    public function getFailure(): self
    {
        $failure = clone $this;
        $failure->reports = $this->reports->filter(fn (RuStoreSingleReport $report) => $report->isFailure());

        return $failure;
    }

    /**
     * Получение отправляемого сообщения.
     *
     * @return RuStoreMessage
     */
    public function getMessage(): RuStoreMessage
    {
        return $this->message;
    }
}
