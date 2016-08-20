<?php

namespace NotificationChannels\Gammu\Models;

class OutboxMultipart extends ModelAbstract
{
    protected $table = 'outbox_multipart';

    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;
}
