<?php

namespace NotificationChannels\Gammu;

use Illuminate\Database\Eloquent\Model;

abstract class ModelAbstract extends Model
{
    protected $connection = 'gammu';
    
    protected $primaryKey = 'ID';
}
