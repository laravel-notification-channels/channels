<?php

namespace NotificationChannels\Onewaysms\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class OnewaysmsException extends Exception
{
    public static function configurationNotSet()
    {
        return new static('OneWaySMS configuration is not set. Please check on your config/services.php');
    }

    public static function respondedWithAnError(ClientException $e)
    {
        return new static('OneWaySMS responded with an error ('.$e->getCode().' : '.$e->getMessage().')');
    }

    public static function couldNotCommunicate(GuzzleException $e)
    {
        return new static('OneWaySMS : The communication with OneWaySMS failed. ('.$e->getCode().' : '.$e->getMessage().')');
    }

    public static function invalidAPIAccount()
    {
        return new static('OneWaySMS : API Username or API Password is invalid (Error Code : -100)');
    }

    public static function invalidSenderID()
    {
        return new static('OneWaySMS : Sender ID parameter is invalid (Error Code : -200)');
    }

    public static function invalidMobileNo()
    {
        return new static('OneWaySMS : Mobile No parameter is invalid (Error Code : -300)');
    }

    public static function invalidLanguageType()
    {
        return new static('OneWaySMS : Language Type parameter is invalid (Error Code : -400)');
    }

    public static function invalidCharactersInMessage()
    {
        return new static('OneWaySMS : Invalid characters in message (Error Code : -500)');
    }

    public static function invalidInsufficientCreditBalance()
    {
        return new static('OneWaySMS : Insufficient credit balance (Error Code : -600)');
    }
}
