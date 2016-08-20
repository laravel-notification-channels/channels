<?php

namespace NotificationChannels\Gammu\Models;

class Phone extends ModelAbstract
{
    protected $table = 'phones';

    protected $guarded = ['*'];

    public $incrementing = false;

    public $timestamps = true;

    const CREATED_AT = 'InsertIntoDB';

    const UPDATED_AT = 'UpdatedInDB';
}
