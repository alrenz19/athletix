<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportsProgram extends Model
{
    protected $table = 'sports_programs';
    protected $fillable = [
        'sport_id', 'category', 'assoc_1', 'assoc_2', 'assoc_3a', 'assoc_3b', 'assoc_other',
        'league_active_1', 'league_count_1', 'league_active_2', 'league_count_2',
        'league_active_3', 'league_count_3', 'league_active_4', 'league_count_4',
        'league_active_5', 'league_count_5', 'well_1', 'well_2', 'well_3',
        'cpd_1', 'cpd_2', 'cpd_3', 'cpd_4', 'cpd_5', 'cpd_6', 'cpd_7'
    ];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }
}