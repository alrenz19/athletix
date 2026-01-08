<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';   // <-- explicitly set
    protected $table = 'event_registrations';

    protected $fillable = [
        'event_id',
        'athlete_id',
        'status',
    ];

    // Relationships
    public function event()
    {
        // explicitly set FK and PK
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function athlete()
    {
        return $this->belongsTo(Athlete::class, 'athlete_id', 'athlete_id');
    }
}
