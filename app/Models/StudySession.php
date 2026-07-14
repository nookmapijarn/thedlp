<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudySession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'short_video_id',
        'type',
        'accessed_at',
        'exited_at',
        'duration',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function shortVideo()
    {
        return $this->belongsTo(ShortVideo::class);
    }
}
