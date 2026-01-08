<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Athlete extends Model
{
    use HasFactory;
    protected $table = 'athletes';
    protected $primaryKey = 'athlete_id';
    protected $fillable = [
        'full_name', 'age', 'profile_url', 'birthdate', 'gender', 'academic_course',
        'highest_competition_level', 'highest_accomplishment', 'international_competition_name',
        'training_seminars_regional', 'training_seminars_national', 'training_seminars_international',
        'training_frequency_days', 'training_hours_per_day', 'scholarship_status',
        'monthly_living_allowance', 'board_lodging_support', 'medical_insurance_support',
        'training_uniforms_support', 'average_tournament_allowance', 'playing_uniforms_sponsorship',
        'playing_gears_sponsorship', 'excused_from_academic_obligations', 'flexible_academic_schedule',
        'academic_tutorials_support', 'year_level', 'section_id', 'sport_id', 'user_id',
        'status', 'conditions', 'school_id', 'removed'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'training_seminars_regional' => 'boolean',
        'training_seminars_national' => 'boolean',
        'training_seminars_international' => 'boolean',
        'board_lodging_support' => 'boolean',
        'medical_insurance_support' => 'boolean',
        'training_uniforms_support' => 'boolean',
        'playing_uniforms_sponsorship' => 'boolean',
        'playing_gears_sponsorship' => 'boolean',
        'excused_from_academic_obligations' => 'boolean',
        'flexible_academic_schedule' => 'boolean',
        'academic_tutorials_support' => 'boolean',
    ];


    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class, 'sport_id');
    }

    public function coach()
    {
        return $this->hasOne(Coach::class, 'sport_id', 'sport_id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'athlete_team', 'athlete_id', 'team_id');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'athlete_event', 'athlete_id', 'event_id')
                    ->withTimestamps();
    }

    public function trainingNotes()
    {
        return $this->hasMany(TrainingNote::class, 'athlete_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'athlete_id', 'athlete_id');
    }

    public function performances()
    {
        return $this->hasMany(Performance::class, 'athlete_id', 'athlete_id');
    }

    public function awards()
    {
        return $this->hasMany(Award::class, 'athlete_id', 'athlete_id');
    }

    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class, 'athlete_id', 'athlete_id');
    }

    public function getProfileUrlAttribute($value)
    {
        // If value is empty, return gender-based default image
        if (empty($value)) {
            switch (strtolower($this->gender)) {
                case 'male':
                    return asset('images/default_male.jpg');
                case 'female':
                    return asset('images/default_female.jfif');
                default:
                    return asset('images/default_neutral.webp');
            }
        }

        // If already a full URL (starts with http), return it directly
        if (str_starts_with($value, 'http')) {
            return $value;
        }

        // Otherwise assume it's a relative path stored in 'storage'
        return asset('storage/' . $value);
    }

    // ✅ Corrected helper methods
    public function attendanceForEvent($eventId)
    {
        return $this->attendances()->where('event_id', $eventId)->first();
    }

    public function performanceForEvent($eventId)
    {
        return $this->performances()->where('event_id', $eventId)->first();
    }
}
