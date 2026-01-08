<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $primaryKey = 'department_id';
    protected $fillable = [
        'department_name',
        'removed'
    ];

    public function courses()
    {
        return $this->hasMany(Course::class, 'department_id');
    }
}
