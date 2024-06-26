<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity1 extends Model
{
    use HasFactory;

    protected $table = 'activity1';
    protected $fillable = [
        'STD_CODE',
        'ACTIVITY',
        'SEMESTRY',
        'HOUR',
        'TRANSFER',
        'TRNTYPE',
        'UPDATED_AT',
        'CREATED_AT'
    ];
}