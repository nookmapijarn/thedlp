<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortVideoLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'short_video_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function shortVideo()
    {
        return $this->belongsTo(ShortVideo::class, 'short_video_id');
    }
}
