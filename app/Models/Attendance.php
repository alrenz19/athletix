<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance'; // table name explicitly
    protected $primaryKey = 'attendance_id';
    protected $fillable = [
        'athlete_id',
        'event_id',
        'status',
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
}
