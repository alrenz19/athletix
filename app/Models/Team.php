<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $primaryKey = 'team_id';
    protected $fillable = ['team_name', 'sport_id', 'removed'];

    // Team belongs to a sport
    public function sport()
    {
        return $this->belongsTo(Sport::class, 'sport_id', 'sport_id');
    }

    // Team has many athletes (many-to-many)
    public function athletes()
    {
        return $this->belongsToMany(Athlete::class, 'athlete_team', 'team_id', 'athlete_id')->withTimestamps();
    }
}
