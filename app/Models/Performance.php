<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    use HasFactory;
    protected $table = 'performance';
    protected $primaryKey = 'performance_id';
    protected $fillable = [
        'athlete_id',
        'event_id',
        'score',
        'remarks',
        'removed',
    ];

    // Relationships
    public function athlete()
    {
        return $this->belongsTo(Athlete::class, 'athlete_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function awards()
    {
        return $this->hasMany(Award::class, 'event_id', 'event_id')
            ->where('athlete_id', $this->athlete_id); // use the current model's athlete_id
    }
}
