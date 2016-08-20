<?php

namespace NotificationChannels\Gammu;

use ModelAbstract;

class OutboxMultipart extends ModelAbstract
{
    protected $table = 'outbox_multipart';
    
    protected $guarded = [];
    
    public $incrementing = false;
    
    public $timestamps = false;
}
