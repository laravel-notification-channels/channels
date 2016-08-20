<?php

namespace NotificationChannels\Gammu\Models;

use Illuminate\Database\Eloquent\Model;

abstract class ModelAbstract extends Model
{
    protected $connection = 'gammu';

    protected $primaryKey = 'ID';
}
