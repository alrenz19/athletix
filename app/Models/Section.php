<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $primaryKey = 'section_id';
    protected $fillable = [
        'section_name',
        'course_id',
        'removed'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function athletes()
    {
        return $this->hasMany(Athlete::class, 'section_id');
    }
}
