<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Http\Controllers\EventoController;

class Calendars extends Model
{
    protected $fillable = [
        'name',
        'type',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'calendar_user' ,'calendar_id', 'user_id')
                    ->withTimestamps();
    }
    public function events() {
        return $this->hasMany(Evento::class);
    }
}
