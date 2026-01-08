<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'module', 'description', 'ip_address'
    ];

    public function user()
    {
        // Match audit_logs.user_id → users.user_id
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
