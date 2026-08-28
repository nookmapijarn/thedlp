<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'questions';
    protected $fillable = ['quiz_id', 'question_text', 'question_image', 'question_type', 'score', 'standard', 'indicator', 'topic', 'taxonomy_level'];
}