<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NonVarsityClub extends Model
{
    protected $table = 'sports'; // Now using the sports table
    
    protected $fillable = [
        'sport_name', 'sport_type', 'sports_sex', 'club_moderator'
    ];

    // Scope to get only non-varsity clubs
    public function scopeNonVarsity($query)
    {
        return $query->where('sport_type', 'non_varsity');
    }

    // Scope to get only clubs (non-varsity and club types)
    public function scopeClubs($query)
    {
        return $query->whereIn('sport_type', ['non_varsity', 'club']);
    }

    // Override the default table name if you want to keep the model name
    public function getTable()
    {
        return 'sports';
    }
}