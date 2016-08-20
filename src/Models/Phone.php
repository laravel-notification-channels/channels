<?php

namespace NotificationChannels\Gammu;

use ModelAbstract;

class Phone extends Model
{
    protected $table = 'phones';
    
    protected $guarded = ['*'];
    
    public $incrementing = false;
    
    public $timestamps = true;
    
    const CREATED_AT = 'InsertIntoDB';
    
    const UPDATED_AT = 'UpdatedInDB';
}
