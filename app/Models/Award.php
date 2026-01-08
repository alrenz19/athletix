<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    protected $fillable = ['athlete_id', 'event_id', 'title', 'description'];

    public function athlete()
    {
        return $this->belongsTo(Athlete::class, 'athlete_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
