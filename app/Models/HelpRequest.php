<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'subject',
        'category',
        'message',
        'status',
        'admin_reply',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(HelpRequestLog::class)->orderBy('created_at', 'asc');
    }

    public function notifications()
    {
        return $this->hasMany(HelpNotification::class);
    }
}
