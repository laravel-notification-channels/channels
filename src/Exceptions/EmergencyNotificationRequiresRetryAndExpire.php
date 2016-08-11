<?php

namespace NotificationChannels\PushoverNotifications\Exceptions;

class EmergencyNotificationRequiresRetryAndExpire extends \Exception {

    protected $message = 'Priority set to emergency, but no retry or expire time supplied.';

}