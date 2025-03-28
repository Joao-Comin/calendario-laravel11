<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarUser extends Model
{
    protected $table = 'calendar_user';
    protected $fillable = ['user_id', 'calendar_id'];

    public $timestamps = true;
}
