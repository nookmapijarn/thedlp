<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject1 extends Model
{
    use HasFactory;

    protected $table = 'subject1';
    protected $fillable = [
        'SUB_CODE',
        'ESUB_CODE',
        'SUB_NAME',
        'ESUB_NAME',
        'SUB_TYPE',
        'ELECT_TYPE',
        'SUB_CREDIT',
        'PREREQ',
        'WORDSEP',
        'EWORDSEP',
        'CANCEL_SEM',
        'NUMCHOICE',
        'DISABLE',
        'SARAGRP',
        'COURSE',
        'LEVEL',
        'MIDTERM',
        'FINAL',
        'MINFIN',
        'RMIDTERM',
        'RFINAL',
        'RMINFIN',
        'SUB_ID',
        'DEFMIDFIN',
        'DEFRMIDFIN',
        'DEFMINFIN',
        'DEFRMINFIN',
        'UPDATED_AT',
        'CREATED_AT'
    ];
}