<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AthleteTeam extends Model
{
    use HasFactory;

    protected $table = 'athlete_team'; // since not plural

    protected $fillable = [
        'athlete_id',
        'team_id',
    ];

    // Relationships
    public function athlete()
    {
        return $this->belongsTo(Athlete::class, 'athlete_id', 'athlete_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }
}
