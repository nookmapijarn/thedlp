<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity1 extends Model
{
    use HasFactory;

    protected $table = 'activity1';
    protected $fillable = [
        'std_code',
        'activity',
        'semestry',
        'hour',
        // 'transfer',
        // 'trntype'
    ];
}