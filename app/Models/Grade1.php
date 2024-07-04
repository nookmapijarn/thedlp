<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade1 extends Model
{
    use HasFactory;

    protected $table = 'grade1';
    protected $fillable = [
        'std_code',
        'semestry',
        'sub_code',
        // 'method',
        // 'learning',
        'typ_code',
        'midterm',
        'final',
        'total',
        'grade',
        'fld_code',
        'roomno',
        'grp_code',
        'midterm1',
        'midterm2',
        'midterm3',
        'midterm4',
        'midterm5',
        'midterm6',
        'midterm7',
        'midterm8',
        'midterm9',
        // 'final1',
        // 'final2',
        // 'book',
        // 'dt_addmid',
        // 'dt_updmid',
        // 'username',
        // '_nullflags'
    ];
}