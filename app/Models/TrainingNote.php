<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingNote extends Model
{
    protected $primaryKey = 'note_id';
    protected $fillable = ['coach_id', 'athlete_id', 'note'];

    public function coach()
    {
        return $this->belongsTo(Coach::class, 'coach_id');
    }

    public function athlete()
    {
        return $this->belongsTo(Athlete::class, 'athlete_id');
    }
}
