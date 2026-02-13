<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'quizzes'; // ชื่อตารางใน DB
    protected $fillable = ['title', 'description', 'subject_code', 'total_score', 'time_limit', 'created_by'];
}