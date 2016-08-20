<?php

namespace NotificationChannels\Gammu\Models;

class Outbox extends ModelAbstract
{
    protected $connection = 'gammu';

    protected $table = 'outbox';

    protected $fillable = [
        'DestinationNumber', 'TextDecoded',
        'SendingDateTime', 'Coding', 'UDH', 'MultiPart', 'SenderID',
        'DeliveryReport', 'CreatorID',
    ];

    public $timestamps = true;

    const CREATED_AT = 'InsertIntoDB';

    const UPDATED_AT = 'UpdatedInDB';
}
