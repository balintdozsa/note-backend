<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoteReminder extends Model
{
    protected $fillable = [
        'note_id',
        'utc_notification_time',
        'local_notification_time',
        'local_time_zone',
        'sent',
    ];
}
