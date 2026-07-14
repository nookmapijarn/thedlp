<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'course_id',
        'type',
        'title',
        'description',
        'video_path',
        'images',
        'audio_path',
        'views_count',
        'likes_count',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function likes()
    {
        return $this->hasMany(ShortVideoLike::class);
    }

    public function isLikedBy($user)
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'short_video_id');
    }

    public function comments()
    {
        return $this->hasMany(ShortVideoComment::class, 'short_video_id')->whereNull('parent_id')->orderBy('created_at', 'desc');
    }

    public function allCommentsCount()
    {
        return $this->hasMany(ShortVideoComment::class, 'short_video_id')->count();
    }
}
