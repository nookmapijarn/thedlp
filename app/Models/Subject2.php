<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject2 extends Model
{
    use HasFactory;

    protected $table = 'subject2';
    protected $fillable = [
        'sub_code',
        'esub_code',
        'sub_name',
        // 'esub_name',
        'sub_type',
        'elect_type',
        'sub_credit',
        // 'prereq',
        // 'wordsep',
        // 'ewordsep',
        // 'cancel_sem',
        // 'numchoice',
        // 'disable',
        // 'saragrp',
        // 'course',
        // 'level',
        // 'midterm',
        // 'final',
        // 'minfin',
        // 'rmidterm',
        // 'rfinal',
        // 'rminfin',
        // 'sub_id',
        // 'defmidfin',
        // 'defrmidfin',
        // 'defminfin',
        // 'defrminfin'
    ];
}