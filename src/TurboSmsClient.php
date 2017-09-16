<?php

namespace NotificationChannels\TurboSms;

use SoapClient;
use NotificationChannels\TurboSms\Exceptions\AuthException;
use NotificationChannels\TurboSms\Exceptions\BalanceException;
use NotificationChannels\TurboSms\Exceptions\CouldNotSendNotification;

class TurboSmsClient
{
    /** @var SoapClient */
    private $client;

    /** @var string */
    public static $host = 'http://turbosms.in.ua/api/wsdl.html';

    /** @var string */
    protected $login;

    /** @var string */
    protected $password;

    const AUTH_SUCCESSFUL = 'Вы успешно авторизировались';
    const AUTH_ERROR_NEED_MORE_PARAMS = 'Не достаточно параметров для выполнения функции';
    const AUTH_ERROR_WRONG_CREDENTIALS = 'Неверный логин или пароль';
    const AUTH_ERROR_ACCOUNT_NOT_ACTIVATED = 'Ваша учётная запись не активирована, свяжитесь с администрацией';
    const AUTH_ERROR_ACCOUNT_BLOCKED = 'Ваша учётная запись заблокирована за нарушения, свяжитесь с администрацией';
    const AUTH_ERROR_ACCOUNT_DISABLED = 'Ваша учётная запись отключена, свяжитесь с администрацией';

    const UNAUTHORISED = 'Вы не авторизированы';
    const SUCCESSFUL_SEND = 'Сообщения успешно отправлены';

    /**
     * TurboSmsClient constructor.
     *
     * @param string $login
     * @param string $password
     * @param string $sender
     */
    public function __construct(string $login, string $password)
    {
        $this->login = $login;
        $this->password = $password;
        $this->client = new SoapClient(self::$host);
    }

    /**
     * @param array  $to String or Array of numbers
     * @param string $message
     *
     * @throws AuthException
     * @throws BalanceException
     * @throws CouldNotSendNotification
     */
    public function send(array $to, string $message, string $sender)
    {
        $authResponse = $this->client->Auth([
            'login'    => $this->login,
            'password' => $this->password,
        ])->AuthResult;

        switch ($authResponse) {
            case self::AUTH_ERROR_NEED_MORE_PARAMS:
                throw AuthException::NeedMoreParams($authResponse);
            case self::AUTH_ERROR_WRONG_CREDENTIALS:
                throw AuthException::WrongCredentials($authResponse);
            case self::AUTH_ERROR_ACCOUNT_NOT_ACTIVATED:
                throw AuthException::AccountError($authResponse);
            case self::AUTH_ERROR_ACCOUNT_BLOCKED:
                throw AuthException::AccountError($authResponse);
            case self::AUTH_ERROR_ACCOUNT_DISABLED:
                throw AuthException::AccountError($authResponse);
        }

        if ($authResponse !== self::AUTH_SUCCESSFUL) {
            throw AuthException::serviceRespondedWithAnError($authResponse);
        }

        $balanceResponse = $this->client->GetCreditBalance()->GetCreditBalanceResult;

        if ($balanceResponse === self::UNAUTHORISED) {
            throw BalanceException::UnAuthorised();
        }

        $balanceResponse = (int) $balanceResponse;
        if ($balanceResponse < count($to)) {
            throw BalanceException::InsufficientBalance($balanceResponse);
        }

        //$to = collect( $to )->toArray();

        $response = $this->client->SendSMS([
            'sender'      => $sender,
            'destination' => implode(',', $to),
            'text'        => $message,
        ]);

        $this->handleProviderResponses($response->SendSMSResult->ResultArray);
    }

    /**
     * @param array $responses
     *
     * @throws CouldNotSendNotification
     */
    protected function handleProviderResponses(array $responses)
    {
        if ($responses[0] !== self::SUCCESSFUL_SEND) {
            throw new CouldNotSendNotification($responses[0]);
        }

        /*
        collect( $responses )->each( function ( stdClass $response ) {
            $errorCode = (int)$response->errorCode;

            if ( $errorCode != self::SUCCESSFUL_SEND ) {
                throw CouldNotSendNotification::serviceRespondedWithAnError( (string)$response->error, $errorCode );
            }
        } );
        */
    }

    /*
    public function getFailedQueueCodes () : array
    {
        return [
            self::AUTH_FAILED,
            self::INVALID_DEST_ADDRESS,
            self::INVALID_API_ID,
            self::CANNOT_ROUTE_MESSAGE,
            self::DEST_MOBILE_BLOCKED,
            self::DEST_MOBILE_OPTED_OUT,
            self::MAX_MT_EXCEEDED,
        ];
    }

    public function getRetryQueueCodes () : array
    {
        return [
            self::NO_CREDIT_LEFT,
            self::INTERNAL_ERROR,
        ];
    }
    */
}
