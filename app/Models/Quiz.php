<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'quizzes'; // ชื่อตารางใน DB
    protected $fillable = [
        'title',
        'description',
        'subject_code',
        'subject_table_type',
        'total_score',
        'time_limit',
        'created_by',
        'subject_group',
        'pass_percentage',
        'grade_level',
        'is_active',
        'enable_proctoring',
        'require_location',
        'require_snapshot',
        'cover_image',
        'certificate_image',
        'certificate_config',
        'certificate_signature'
    ];
}