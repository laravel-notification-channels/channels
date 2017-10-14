<?php

namespace NotificationChannels\RedsmsRu\Exceptions;

class CouldNotSendNotification extends \Exception
{
    /**
     * Thrown when we're redsms.ru responded with error.
     *
     * @param int $errorCode
     * @return static
     */
    public static function redsmsRespondedWithAnError($errorCode)
    {
        switch ($errorCode) {
            case '000':
                $message = 'Сервис отключён';
                break;
            case 1:
                $message = 'Не указана подпись';
                break;
            case 2:
                $message = 'Не указан логин';
                break;
            case 3:
                $message = 'Не указан текст';
                break;
            case 4:
                $message = 'Не указан телефон';
                break;
            case 5:
                $message = 'Не указан отправитель';
                break;
            case 6:
                $message = 'Некорректная подпись';
                break;
            case 7:
                $message = 'Некорректный логин';
                break;
            case 8:
                $message = 'Некорректное имя отправителя';
                break;
            case 9:
                $message = 'Незарегистрированное имя отправителя';
                break;
            case 10:
                $message = 'Неодобренное имя отправителя';
                break;
            case 11:
                $message = 'В тексте содержатся запрещённые слова';
                break;
            case 12:
                $message = 'Ошибка отправки СМС';
                break;
            case 13:
                $message = 'Номер находится в стоп-листе, отправка на этот номер запрещена';
                break;
            case 14:
                $message = 'В запросе более 50 номеров';
                break;
            case 15:
                $message = 'Не указана база';
                break;
            case 16:
                $message = 'Некорректный номер';
                break;
            case 17:
                $message = 'Не указаны ID СМС';
                break;
            case 18:
                $message = 'Не получен статус';
                break;
            case 19:
                $message = 'Пустой ответ';
                break;
            case 20:
                $message = 'Номер уже существует';
                break;
            case 21:
                $message = 'Отсутствует название';
                break;
            case 22:
                $message = 'Шаблон уже существует';
                break;
            case 23:
                $message = 'Не указан месяц (Формат: YYYY-MM)';
                break;
            case 24:
                $message = 'Не указана временная метка';
                break;
            case 25:
                $message = 'Ошибка доступа к базе';
                break;
            case 26:
                $message = 'База не содержит номеров';
                break;
            case 27:
                $message = 'Нет валидных номеров';
                break;
            case 28:
                $message = 'Не указана начальная дата';
                break;
            case 29:
                $message = 'Не указана конечная дата';
                break;
            case 30:
                $message = 'Не указана дата (Формат: YYYY-MM-DD)';
                break;
            default:
                $message = $errorCode;
                break;
        }

        return new static(
            sprintf(
                'redsms.ru responded with an error "%s"', $errorCode == $message ? $errorCode : $errorCode . ': ' . $message
            )
        );
    }

    /**
     * Thrown when recipient's phone number is missing.
     *
     * @return static
     */
    public static function missingRecipient()
    {
        return new static('Notification was not sent. Phone number is missing.');
    }
}
