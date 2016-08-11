<?php

namespace NotificationChannels\PushoverNotifications\Exceptions;

class PushoverEmergencyException extends \Exception {

    protected $message = 'Priority set to emergency, but no retry or expire time supplied.';

}