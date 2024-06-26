<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule1 extends Model
{
    use HasFactory;

    protected $table = 'schedule1';
    protected $fillable = [
        'SEMESTRY',
        'LEARNING',
        'SUB_CODE',
        'EXAM_DAY',
        'FLD_CODE',
        'EXAM_START',
        'EXAM_END',
        'UPDATED_AT',
        'CREATED_AT'
    ];
}