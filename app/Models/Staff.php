<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';
    protected $primaryKey = 'staff_id';
    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'full_name',
        'position',
        'removed',
    ];

    // Relation to User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
