<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'total_score',
        'time_limit',
        'created_by',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}