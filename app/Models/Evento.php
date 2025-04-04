<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start',
        'end',
        'color',
        'task',
        'finalizado',
        'description',
        'user_id',
        'calendar_id',
    ];

    public function User(){
        
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Calendar(){
        
        return $this->belongsTo(Calendars::class, 'calendar_id');
    }
}
