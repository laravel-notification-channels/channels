<?php

namespace NotificationChannels\TurboSms;

use SoapClient;
use NotificationChannels\TurboSms\Exceptions\AuthException;
use NotificationChannels\TurboSms\Exceptions\BalanceException;
use NotificationChannels\TurboSms\Exceptions\CouldNotSendNotification;

class TurboSmsClient
{
    /** @var string */
    public static $host = 'http://turbosms.in.ua/api/wsdl.html';

    /** @var string */
    protected $login;

    /** @var string */
    protected $password;

    /** @var SoapClient */
    protected $client;

    /** @var bool */
    private $connected;

    /** @var array */
    private $lastResults = [];

    public const AUTH_SUCCESSFUL                  = 'Вы успешно авторизировались';
    public const AUTH_ERROR_NEED_MORE_PARAMS      = 'Не достаточно параметров для выполнения функции';
    public const AUTH_ERROR_WRONG_CREDENTIALS     = 'Неверный логин или пароль';
    public const AUTH_ERROR_ACCOUNT_NOT_ACTIVATED = 'Ваша учётная запись не активирована, свяжитесь с администрацией';
    public const AUTH_ERROR_ACCOUNT_BLOCKED       = 'Ваша учётная запись заблокирована за нарушения, свяжитесь с администрацией';
    public const AUTH_ERROR_ACCOUNT_DISABLED      = 'Ваша учётная запись отключена, свяжитесь с администрацией';

    public const UNAUTHORISED    = 'Вы не авторизированы';
    public const SUCCESSFUL_SEND = 'Сообщения успешно отправлены';

    /**
     * TurboSmsClient constructor.
     *
     * @param string $login
     * @param string $password
     */
    public function __construct(string $login, string $password)
    {
        $this->login    = $login;
        $this->password = $password;
        $this->client   = new SoapClient( self::$host );
    }

    /**
     * @param array  $to Array of recipients
     * @param string $message
     * @param string $sender
     *
     * @throws AuthException
     * @throws BalanceException
     * @throws CouldNotSendNotification
     */
    public function send(array $to, string $message, string $sender) : void
    {
        // normalizing input data

        array_walk( $to, function (&$value) {
            $value = '+'.preg_replace( '/\D/', '', $value );
        } );

        $recipients = array_filter( $to, function ($value) {
            return preg_match( '/\+\d{12}/', $value );
        } );

        $message = trim( $message );
        $sender  = trim( $sender );

        // basic versifying and connecting

        $this->verify( $recipients, $message, $sender )->connect()->checkBalance( \count( $recipients ) );

        // sending notification and response handle

        $this->handleProviderResponses( $this->client->SendSMS( [
            'destination' => implode( ',', $recipients ),
            'text'        => $message,
            'sender'      => $sender,
        ] )->SendSMSResult->ResultArray );
    }

    /**
     * @param array  $to
     * @param string $message
     * @param string $sender
     *
     * @return TurboSmsClient
     * @throws CouldNotSendNotification
     */
    public function verify(array $to, string $message, string $sender) : self
    {
        if ( \count( $to ) < 1 ) {
            throw CouldNotSendNotification::RecipientRequired();
        }

        if ( empty( $message ) ) {
            throw CouldNotSendNotification::MessageRequired();
        }

        if ( empty( $sender ) ) {
            throw CouldNotSendNotification::SenderRequired();
        }

        return $this;
    }

    /**
     * Connecting to the Service.
     *
     * @return TurboSmsClient
     * @throws AuthException
     */
    public function connect() : self
    {
        if ( $this->connected ) {
            return $this;
        }

        $authResponse = $this->client->Auth( [
            'login'    => $this->login,
            'password' => $this->password,
        ] )->AuthResult;

        switch ( $authResponse ) {
            case self::AUTH_ERROR_NEED_MORE_PARAMS:
                throw AuthException::NeedMoreParams( $authResponse );
            case self::AUTH_ERROR_WRONG_CREDENTIALS:
                throw AuthException::WrongCredentials( $authResponse );
            case self::AUTH_ERROR_ACCOUNT_NOT_ACTIVATED:
                throw AuthException::AccountError( $authResponse );
            case self::AUTH_ERROR_ACCOUNT_BLOCKED:
                throw AuthException::AccountError( $authResponse );
            case self::AUTH_ERROR_ACCOUNT_DISABLED:
                throw AuthException::AccountError( $authResponse );
        }

        if ( $authResponse !== self::AUTH_SUCCESSFUL ) {
            throw AuthException::serviceRespondedWithAnError( $authResponse );
        }

        $this->connected = true;

        return $this;
    }

    /**
     * @param int $credits
     *
     * @return TurboSmsClient
     * @throws BalanceException
     */
    public function checkBalance(int $credits) : self
    {
        $balanceResponse = $this->client->GetCreditBalance()->GetCreditBalanceResult;

        if ( $balanceResponse === self::UNAUTHORISED ) {
            throw BalanceException::UnAuthorised();
        }

        $balanceResponse = (int) $balanceResponse;
        if ( $balanceResponse < $credits ) {
            throw BalanceException::InsufficientBalance( $balanceResponse );
        }

        return $this;
    }

    /**
     * @param array $responses
     *
     * @return TurboSmsClient
     * @throws CouldNotSendNotification
     */
    private function handleProviderResponses(array $responses) : self
    {
        if ( $responses[ 0 ] !== self::SUCCESSFUL_SEND ) {
            throw CouldNotSendNotification::serviceRespondedWithAnError( $responses[ 0 ] );
        }

        $this->lastResults = $responses;

        return $this;
    }

    /**
     * @return array
     */
    public function getLastResults() : array
    {
        return $this->lastResults;
    }

}
