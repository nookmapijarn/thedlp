<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade1 extends Model
{
    use HasFactory;

    protected $table = 'grade1';
    protected $fillable = [
        'STD_CODE',
        'SEMESTRY',
        'SUB_CODE',
        'METHOD',
        'LEARNING',
        'TYP_CODE',
        'MIDTERM',
        'FINAL',
        'TOTAL',
        'GRADE',
        'FLD_CODE',
        'ROOMNO',
        'GRP_CODE',
        'MIDTERM1',
        'MIDTERM2',
        'MIDTERM3',
        'MIDTERM4',
        'MIDTERM5',
        'MIDTERM6',
        'MIDTERM7',
        'MIDTERM8',
        'MIDTERM9',
        'FINAL1',
        'FINAL2',
        'BOOK',
        'DT_ADDMID',
        'DT_UPDMID',
        'USERNAME',
        '_NULLFLAGS',
        'UPDATED_AT',
        'CREATED_AT'
    ];
}