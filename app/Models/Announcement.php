<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $primaryKey = 'announcement_id';
    protected $fillable = [
        'title',
        'message',
        'posted_by',
        'target',
        'sport_id',
        'section_id',
        'removed'
    ];

    public function poster()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class, 'sport_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
