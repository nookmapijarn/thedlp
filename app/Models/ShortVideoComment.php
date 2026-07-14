<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortVideoComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'short_video_id',
        'user_id',
        'parent_id',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function shortVideo()
    {
        return $this->belongsTo(ShortVideo::class, 'short_video_id');
    }

    public function replies()
    {
        return $this->hasMany(ShortVideoComment::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    public function parent()
    {
        return $this->belongsTo(ShortVideoComment::class, 'parent_id');
    }
}
