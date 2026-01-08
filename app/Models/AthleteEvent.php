<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AthleteEvent extends Model
{
    use HasFactory;

    protected $table = 'athlete_event'; // pivot table name
    protected $primaryKey = 'id'; // if you have an auto-increment primary key, otherwise set $primaryKey = null
    public $incrementing = true; // set false if no primary key
    protected $keyType = 'int';

    protected $fillable = [
        'athlete_id',
        'event_id',
        'created_at',
        'updated_at',
    ];

    public function athlete()
    {
        return $this->belongsTo(Athlete::class, 'athlete_id', 'athlete_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }
}
