<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;

    protected $primaryKey = 'coach_id';
    
    protected $fillable = [
        'user_id',
        'full_name',
        'age',
        'gender',
        'specialization',
        'sport_id',
        'current_position_title',
        'sports_program_position',
        'employment_status',
        'monthly_salary',
        'years_experience',
        'was_previous_athlete',
        'highest_competition_level',
        'highest_accomplishment_athlete',
        'international_competition_athlete',
        'highest_accomplishment_coach',
        'international_competition_coach',
        'regional_membership',
        'national_membership',
        'international_membership',
        'international_membership_name',
        'highest_degree',
        'bachelor_degree',
        'master_degree',
        'doctorate_degree',
        'removed'
    ];

    protected $casts = [
        'was_previous_athlete' => 'boolean',
        'regional_membership' => 'boolean',
        'national_membership' => 'boolean',
        'international_membership' => 'boolean',
        'monthly_salary' => 'decimal:2',
        'age' => 'integer',
        'years_experience' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class, 'sport_id');
    }

    public function trainingNotes()
    {
        return $this->hasMany(TrainingNote::class, 'coach_id');
    }

    public function athletes()
    {
        return $this->hasMany(Athlete::class, 'sport_id', 'sport_id');
    }
}