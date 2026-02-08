<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $primaryKey = 'event_id';
    public $incrementing = true;          // ensure auto-increment works
    protected $keyType = 'int';  
    protected $fillable = [
        'sport_id',
        'event_name',
        'event_date',
        'event_type',
        'location',
        'removed'
    ];

    protected $casts = [
        'event_date' => 'datetime:Y-m-d H:i:s',
    ];

    public function sport()
    {
        return $this->belongsTo(Sport::class, 'sport_id', 'sport_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'event_id', 'event_id');
    }

    public function performances()
    {
        return $this->hasMany(Performance::class, 'event_id', 'event_id');
    }

    public function athletes()
    {
        return $this->belongsToMany(Athlete::class, 'athlete_event', 'event_id', 'athlete_id')
                    ->withTimestamps();
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class, 'event_id', 'event_id');
    }

    public function athleteEvents()
    {
        return $this->hasMany(AthleteEvent::class, 'event_id');
    }

    // Add this accessor to force Manila timezone
    public function getEventDateAttribute($value)
    {
        if (!$value) return null;
        
        // If it's already a Carbon instance
        if ($value instanceof \Carbon\Carbon) {
            return $value->copy()->setTimezone('Asia/Manila');
        }
        
        // If it's a string, parse it as Manila time
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value, 'Asia/Manila');
    }
}
