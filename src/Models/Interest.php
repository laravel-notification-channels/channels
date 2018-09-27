<?php

namespace NotificationChannels\ExpoPushNotifications\Models;

use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    /**
     * The associated table.
     *
     * @var string
     */
    protected $table;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Interest constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('exponent-push-notifications.interests.database.table_name');

        parent::__construct($attributes);
    }
}
