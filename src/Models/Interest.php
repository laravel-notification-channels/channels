<?php

namespace NotificationChannels\ExpoPushNotifications\Models;

use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    protected $table;

    protected $fillable = [
        'key',
        'value',
    ];

    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        $this->table = config('exponent-push-notifications.interests.database.table_name');

        parent::__construct($attributes);
    }
}