<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Petition extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'title',
        'type',
        'description',
        'file_path',
        'status',
        'admin_comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
