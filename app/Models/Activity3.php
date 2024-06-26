<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity3 extends Model
{
    use HasFactory;

    protected $table = 'activity3';
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