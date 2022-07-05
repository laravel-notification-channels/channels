<?php

namespace NotificationChannels\ArkeselSms\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($response)
    {
        if ($response->getStatusCode() === 100) {
            $errorMsg =   "Bad gateway request";
        } elseif ($response->getStatusCode() === 101) {
            $errorMsg =   "Wrong action";
        } elseif ($response->getStatusCode() === 102) {
            $errorMsg =   "Authentication failed";
        } elseif ($response->getStatusCode() === 103) {
            $errorMsg =   "Invalid phone number";
        } elseif ($response->getStatusCode() === 104) {
            $errorMsg =   "Phone coverage not active";
        } elseif ($response->getStatusCode() === 105) {
            $errorMsg =   "Insufficient balance";
        } elseif ($response->getStatusCode() === 106) {
            $errorMsg =   "Invalid Sender ID";
        } elseif ($response->getStatusCode() === 109) {
            $errorMsg =   "Invalid Schedule Time";
        } elseif ($response->getStatusCode() === 111) {
            $errorMsg =    "SMS contains spam word. Wait for approval";
        } elseif ($response->getStatusCode() === 401) {
            $errorMsg = "Authentication failed";
        } elseif ($response->getStatusCode() === 402) {
            $errorMsg = "Insufficient balance";
        } elseif ($response->getStatusCode() === 403) {
            $errorMsg = "Inactive Gateway";
        } elseif ($response->getStatusCode() === 422) {
            $errorMsg = "Validation Errors";
        } elseif ($response->getStatusCode() === 500) {
            $errorMsg = "Internal error";
        } else {
            $errorMsg  = 'Unknown Error';
        }

        return new static($errorMsg);
    }
}
