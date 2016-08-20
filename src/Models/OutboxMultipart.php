<?php

namespace NotificationChannels\Gammu\Models;

use NotificationChannels\Gammu\Models\ModelAbstract;

class OutboxMultipart extends ModelAbstract
{
    protected $table = 'outbox_multipart';
    
    protected $guarded = [];
    
    public $incrementing = false;
    
    public $timestamps = false;
}
