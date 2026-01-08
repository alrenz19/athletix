<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    protected $fillable = [
        'username',
        'password',
        'role',
        'removed',
    ];

    protected $hidden = [
        'password',
    ];

    public function staff()
    {
        return $this->hasOne(Staff::class, 'user_id');
    }

    public function coach()
    {
        return $this->hasOne(Coach::class, 'user_id');
    }

    public function athlete()
    {
        return $this->hasOne(Athlete::class, 'user_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'posted_by');
    }

    public function otps()
    {
        return $this->hasMany(Otp::class, 'user_id');
    }

}
