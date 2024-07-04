<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule2 extends Model
{
    use HasFactory;

    protected $table = 'schedule2';
    protected $fillable = [
        'semestry',
        'learning',
        'sub_code',
        'exam_day',
        'fld_code',
        'exam_start',
        'exam_end'
    ];
}