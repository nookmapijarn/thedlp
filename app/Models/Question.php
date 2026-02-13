<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'questions';
    protected $fillable = ['quiz_id', 'question_text', 'question_type', 'score', 'indicator', 'taxonomy_level'];
}